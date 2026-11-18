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

    public function test_finance_signatures_can_be_configured_and_reflected_on_report()
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

        // 1. Can view signatures form
        $response = $this->get('/admin/finance/signatures');
        $response->assertStatus(200);
        $response->assertSee('Pengaturan Penanda Tangan Laporan');

        // 2. Can update signatures
        $postData = [
            'report_city' => 'Padang',
            'report_signer_1_title' => 'Disiapkan Oleh,',
            'report_signer_1_name' => 'Erima Oneto',
            'report_signer_1_role' => 'Finance Specialist',
            'report_signer_2_title' => 'Disetujui Oleh,',
            'report_signer_2_name' => 'Erickman Direktur',
            'report_signer_2_role' => 'Direktur Utama',
        ];

        $postResponse = $this->post('/admin/finance/signatures', $postData);
        $postResponse->assertRedirect('/admin/finance/signatures');
        $postResponse->assertSessionHas('success');

        // 3. Reflected in report
        $reportResponse = $this->get('/admin/finance/report');
        $reportResponse->assertStatus(200);
        $reportResponse->assertSee('Padang,');
        $reportResponse->assertSee('Erima Oneto');
        $reportResponse->assertSee('Finance Specialist');
        $reportResponse->assertSee('Erickman Direktur');
        $reportResponse->assertSee('Direktur Utama');
    }

    public function test_hostinger_webmail_inbox_and_inquiry_tabs_accessible_in_admin()
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

        // 1. Hostinger tab
        $responseHostinger = $this->get('/admin/email/inbox?tab=hostinger');
        $responseHostinger->assertStatus(200);
        $responseHostinger->assertSee('finance@erickman.co.id');
        $responseHostinger->assertSee('Email Hostinger');

        // 2. Inquiries tab
        $responseInquiries = $this->get('/admin/email/inbox?tab=inquiries');
        $responseInquiries->assertStatus(200);
        $responseInquiries->assertSee('Formulir Website (Inquiries)');
    }

    public function test_keuangan_user_can_access_email_module()
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

        // Keuangan can access inbox
        $responseInbox = $this->get('/admin/email/inbox');
        $responseInbox->assertStatus(200);
        $responseInbox->assertSee('Kotak Masuk (Webmail)');

        // Keuangan can access compose
        $responseCompose = $this->get('/admin/email/compose');
        $responseCompose->assertStatus(200);
        $responseCompose->assertSee('finance@erickman.co.id');

        // Keuangan can access outbox
        $responseOutbox = $this->get('/admin/email/outbox');
        $responseOutbox->assertStatus(200);
        $responseOutbox->assertSee('Log Email Terkirim');
    }

    public function test_fleet_table_view_and_report_access()
    {
        $admin = User::where('role', 'superadmin')->first();
        $this->actingAs($admin);

        // Table view
        $responseTable = $this->get('/admin/fleets?view=table');
        $responseTable->assertStatus(200);
        $responseTable->assertSee('Tampilan Tabel');
        $responseTable->assertSee('No. Urut');

        // Print report
        $responseReport = $this->get('/admin/fleets/report');
        $responseReport->assertStatus(200);
        $responseReport->assertSee('REKAPITULASI DATA ARMADA');
        $responseReport->assertSee('PT. ERICKMAN SARANA ABADI');
    }

    public function test_fleet_types_crud()
    {
        $admin = User::where('role', 'superadmin')->first();
        $this->actingAs($admin);

        // Fleet types list
        $responseTypes = $this->get('/admin/fleets/types');
        $responseTypes->assertStatus(200);
        $responseTypes->assertSee('Kelola Tipe Armada');

        // Create new fleet type
        $responseStore = $this->post('/admin/fleets/types', [
            'name' => 'Armada Khusus Testing',
            'description' => 'Tipe khusus untuk pengujian sistem',
        ]);
        $responseStore->assertRedirect('/admin/fleets/types');
        $this->assertDatabaseHas('fleet_types', ['name' => 'Armada Khusus Testing']);
    }

    public function test_gtm_crud_and_report_access()
    {
        $admin = User::where('role', 'superadmin')->first();
        $this->actingAs($admin);

        \App\Models\GtmModule::where('tube_code', 'like', 'GTM-TEST-%')->delete();
        $testCode = 'GTM-TEST-' . time();

        // GTM index
        $responseIndex = $this->get('/admin/gtm');
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('Data GTM (Gas Transport Module)');

        // GTM create form
        $responseCreate = $this->get('/admin/gtm/create');
        $responseCreate->assertStatus(200);
        $responseCreate->assertSee('Tambah Tabung GTM Baru');

        // GTM store
        $responseStore = $this->post('/admin/gtm', [
            'order_number' => 1,
            'tube_code' => $testCode,
            'tube_brand' => 'Worthington',
            'capacity' => '1.200 Nm3',
            'manufacture_year' => '2023',
            'location' => 'Depo Cikarang',
            'status' => 'aktif',
            'notes' => 'Tabung pengujian sistem otomatis',
        ]);
        $responseStore->assertRedirect('/admin/gtm');
        $this->assertDatabaseHas('gtm_modules', [
            'tube_code' => $testCode,
            'location' => 'Depo Cikarang',
        ]);

        // GTM print report
        $responseReport = $this->get('/admin/gtm/report');
        $responseReport->assertStatus(200);
        $responseReport->assertSee('DATA GTM (GAS TRANSPORT MODULE)');
        $responseReport->assertSee('Worthington');
        $responseReport->assertSee('Depo Cikarang');

        // Clean up
        \App\Models\GtmModule::where('tube_code', $testCode)->delete();
    }
}





