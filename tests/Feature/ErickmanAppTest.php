<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Inquiry;
use App\Models\Fleet;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ErickmanAppTest extends TestCase
{
    public function test_homepage_loads_successfully_with_company_profile()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('PT. Erickman Sarana Abadi');
        $response->assertSee('Energy & Transportation', false);
        $response->assertSee('2211210015706'); // NIB Resmi
        $response->assertSee('18 Office Park'); // Alamat Resmi
        $response->assertSee('truck-cng-green.jpg');
        $response->assertSee('truck-box-red.jpg');
    }

    public function test_service_detail_page_loads()
    {
        $response = $this->get('/layanan/distribusi-pengadaan-gas-alam');

        $response->assertStatus(200);
        $response->assertSee('Distribusi &amp; Pengadaan Gas Alam', false);
    }

    public function test_public_contact_form_submits_inquiry()
    {
        $inquiryData = [
            'name' => 'Budi Santoso',
            'company' => 'PT Surya Energi Makmur',
            'email' => 'budi@suryaenergi.com',
            'phone' => '08123456789',
            'service_interest' => 'Distribusi & Pengadaan Gas Alam (CNG / LNG)',
            'subject' => 'Inquiry Gas CNG 1000 m3',
            'message' => 'Mohon penawaran harga pengadaan gas CNG untuk boiler industri kami.',
        ];

        $response = $this->post('/kirim-pesan', $inquiryData);

        $response->assertSessionHas('success_inquiry');
        $this->assertDatabaseHas('inquiries', [
            'email' => 'budi@suryaenergi.com',
            'subject' => 'Inquiry Gas CNG 1000 m3',
        ]);
    }

    public function test_admin_login_and_protected_dashboard_access()
    {
        // 1. Guest redirected to login
        $guestResponse = $this->get('/admin/dashboard');
        $guestResponse->assertRedirect('/login');

        // 2. Login as admin
        $admin = User::where('email', 'admin@erickman.co.id')->first();
        $this->assertNotNull($admin);

        $loginResponse = $this->post('/login', [
            'email' => 'admin@erickman.co.id',
            'password' => 'admin12345',
        ]);

        $loginResponse->assertRedirect('/admin/dashboard');

        // 3. Authenticated dashboard access
        $dashboardResponse = $this->actingAs($admin)->get('/admin/dashboard');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Dashboard Utama');
    }

    public function test_admin_fleet_management_access()
    {
        $admin = User::where('email', 'admin@erickman.co.id')->first();
        $response = $this->actingAs($admin)->get('/admin/fleets');

        $response->assertStatus(200);
        $response->assertSee('Armada Truk Operasional');
        $response->assertSee('B 9108 UEM'); // Truk Hijau CNG
        $response->assertSee('T 8434 DY');  // Truk Merah Dyna
    }

    public function test_admin_finance_dashboard_access()
    {
        $admin = User::where('email', 'admin@erickman.co.id')->first();
        $response = $this->actingAs($admin)->get('/admin/finance');

        $response->assertSee('Buku Transaksi Keuangan');
    }

    public function test_admin_email_inbox_access()
    {
        $admin = User::where('email', 'admin@erickman.co.id')->first();
        $response = $this->actingAs($admin)->get('/admin/email/inbox');

        $response->assertStatus(200);
        $response->assertSee('Kotak Masuk Website');
    }

    public function test_admin_content_settings_access()
    {
        $admin = User::where('email', 'admin@erickman.co.id')->first();
        $response = $this->actingAs($admin)->get('/admin/content/settings');

        $response->assertStatus(200);
        $response->assertSee('2211210015706');
    }

    public function test_login_page_does_not_expose_credentials()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertDontSee('admin12345');
        $response->assertDontSee('Kredensial Default Login');
        $response->assertSee('Proteksi Anti Brute-Force');
    }

    public function test_login_honeypot_blocks_automated_submissions()
    {
        $response = $this->post('/login', [
            'email' => 'admin@erickman.co.id',
            'password' => 'admin12345',
            '_hp_security_check' => 'im_a_bot',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }
}

