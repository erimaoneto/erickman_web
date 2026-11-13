<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Services\Security\FileIntegrityService;
use App\Services\Security\SafeOutboundClient;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SecuritySentinelTest extends TestCase
{
    public function test_security_audit_command_runs_successfully()
    {
        $this->artisan('security:audit')
            ->assertExitCode(0);
    }

    public function test_server_shields_exist_and_block_script_execution()
    {
        $fileIntegrity = new FileIntegrityService();
        $shields = $fileIntegrity->verifyServerShields();

        $this->assertTrue($shields['images_htaccess']['exists']);
        $this->assertTrue($shields['images_htaccess']['shield_active']);
        $this->assertTrue($shields['root_htaccess']['exists']);
        $this->assertTrue($shields['root_htaccess']['shield_active']);
    }

    public function test_security_headers_middleware_attaches_security_headers()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $this->assertTrue($response->headers->has('Content-Security-Policy'));
    }

    public function test_safe_outbound_client_blocks_ssrf_and_private_ips()
    {
        $client = new SafeOutboundClient();

        // 1. Loopback IP must be blocked
        $resLoopback = $client->validateUrl('http://127.0.0.1:8080/admin');
        $this->assertFalse($resLoopback['is_safe']);

        // 2. Cloud metadata IP (AWS/GCP) must be blocked
        $resMetadata = $client->validateUrl('http://169.254.169.254/latest/meta-data');
        $this->assertFalse($resMetadata['is_safe']);

        // 3. Disallowed scheme (file:// or gopher://) must be blocked
        $resScheme = $client->validateUrl('file:///etc/passwd');
        $this->assertFalse($resScheme['is_safe']);

        // 4. Disallowed port (e.g. 22 or 3306) must be blocked
        $resPort = $client->validateUrl('http://example.com:22/ssh');
        $this->assertFalse($resPort['is_safe']);
    }

    public function test_superadmin_can_access_security_dashboard_and_run_scan()
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@erickman.co.id'],
            [
                'name' => 'Erima Oneto',
                'password' => bcrypt('admin123'),
                'role' => 'superadmin',
                'is_active' => true,
            ]
        );

        $this->actingAs($admin);

        // Access security dashboard
        $response = $this->get('/admin/security');
        $response->assertStatus(200);
        $response->assertSee('Audit & Pencegahan Anomali Server', false);
        $response->assertSee('Integritas File Publik');
        $response->assertSee('Web Server Shield');
        $response->assertSee('Lalu Lintas Keluar (Outbound)');

        // Trigger manual scan
        $scanResponse = $this->post('/admin/security/scan');
        $scanResponse->assertRedirect('/admin/security');
        $scanResponse->assertSessionHas('success');
    }

    public function test_non_admin_cannot_access_security_dashboard()
    {
        $keuangan = User::firstOrCreate(
            ['email' => 'keuangan@erickman.co.id'],
            [
                'name' => 'Staf Keuangan',
                'password' => bcrypt('keuangan123'),
                'role' => 'keuangan',
                'is_active' => true,
            ]
        );

        $this->actingAs($keuangan);

        $response = $this->get('/admin/security');
        $response->assertRedirect('/admin/finance');
    }

    public function test_finance_report_displays_transactions_and_supports_all_months()
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@erickman.co.id'],
            [
                'name' => 'Erima Oneto',
                'password' => bcrypt('admin123'),
                'role' => 'superadmin',
                'is_active' => true,
            ]
        );

        $this->actingAs($admin);

        // 1. Fresh report access without query param
        $response = $this->get('/admin/finance/report');
        $response->assertStatus(200);
        $response->assertSee('PT. ERICKMAN SARANA ABADI');
        $response->assertSee('Rincian Transaksi');

        // 2. Report for all months (Tahunan)
        $responseAll = $this->get('/admin/finance/report?month=all&year=2026');
        $responseAll->assertStatus(200);
        $responseAll->assertSee('Periode Tahun 2026 (Semua Bulan)');
    }
}
