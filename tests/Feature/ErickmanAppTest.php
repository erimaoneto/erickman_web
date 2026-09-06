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
        $response->assertSee('Oil, Gas, &amp; Transportation', false);
        $response->assertSee('2211210015706'); // NIB Resmi
        $response->assertSee('18 Office Park'); // Alamat Resmi
        $response->assertSee('truck-cng-green.jpg');
        $response->assertSee('truck-box-red.jpg');
    }

    public function test_service_detail_page_loads()
    {
        $response = $this->get('/layanan/lpg-liquified-petroleum-gas');

        $response->assertStatus(200);
        $response->assertSee('LPG (Liquified Petroleum Gas)', false);
    }

    public function test_public_contact_form_submits_inquiry()
    {
        $inquiryData = [
            'name' => 'Budi Santoso',
            'company' => 'PT Surya Energi Makmur',
            'email' => 'budi@suryaenergi.com',
            'phone' => '08123456789',
            'service_interest' => 'LPG (Liquified Petroleum Gas)',
            'subject' => 'Inquiry Gas LPG 1000 tabung',
            'message' => 'Mohon penawaran harga pengadaan gas LPG untuk pabrik kami.',
        ];

        $response = $this->post('/kirim-pesan', $inquiryData);

        $response->assertSessionHas('success_inquiry');
        $this->assertDatabaseHas('inquiries', [
            'email' => 'budi@suryaenergi.com',
            'subject' => 'Inquiry Gas LPG 1000 tabung',
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

    public function test_keuangan_user_login_and_finance_access()
    {
        $keuangan = User::where('email', 'keuangan@erickman.co.id')->first();
        $this->assertNotNull($keuangan);

        // Login as keuangan
        $loginResponse = $this->post('/login', [
            'email' => 'keuangan@erickman.co.id',
            'password' => 'keuangan123',
        ]);
        $loginResponse->assertRedirect(route('admin.finance.index'));

        // Access finance index
        $financeResponse = $this->actingAs($keuangan)->get('/admin/finance');
        $financeResponse->assertStatus(200);
        $financeResponse->assertSee('Portal Keuangan');
        $financeResponse->assertSee('Arus Kas & Transaksi', false);

        // Keuangan inputs a new transaction
        $createTrxResponse = $this->actingAs($keuangan)->post('/admin/finance', [
            'type' => 'pemasukan',
            'category' => 'Distribusi Gas CNG',
            'amount' => 25000000,
            'transaction_date' => now()->toDateString(),
            'reference_invoice' => 'INV-TEST-KEUANGAN-01',
            'description' => 'Pembayaran batch pengadaan CNG PT Surya',
        ]);
        $createTrxResponse->assertRedirect(route('admin.finance.index'));

        $this->assertDatabaseHas('transactions', [
            'reference_invoice' => 'INV-TEST-KEUANGAN-01',
            'created_by' => $keuangan->id,
        ]);
    }

    public function test_keuangan_user_restricted_from_superadmin_modules()
    {
        $keuangan = User::where('email', 'keuangan@erickman.co.id')->first();

        // Keuangan cannot access dashboard
        $response = $this->actingAs($keuangan)->get('/admin');
        $response->assertRedirect(route('admin.finance.index'));

        // Keuangan cannot access user management
        $responseUsers = $this->actingAs($keuangan)->get('/admin/users');
        $responseUsers->assertRedirect(route('admin.finance.index'));
    }

    public function test_superadmin_can_view_edit_and_delete_keuangan_transactions()
    {
        $admin = User::where('email', 'admin@erickman.co.id')->first();
        $keuangan = User::where('email', 'keuangan@erickman.co.id')->first();

        $trx = Transaction::create([
            'code' => 'TRX-TEST-999',
            'type' => 'pengeluaran',
            'category' => 'BBM & Bahan Bakar',
            'amount' => 5000000,
            'transaction_date' => now()->toDateString(),
            'reference_invoice' => 'REF-KEUANGAN-999',
            'description' => 'Biaya BBM Truk',
            'created_by' => $keuangan->id,
        ]);

        // Admin sees the transaction and that it was created by Keuangan
        $indexResponse = $this->actingAs($admin)->get('/admin/finance');
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('TRX-TEST-999');
        $indexResponse->assertSee($keuangan->name);

        // Admin can edit the transaction
        $editResponse = $this->actingAs($admin)->put("/admin/finance/{$trx->id}", [
            'type' => 'pengeluaran',
            'category' => 'BBM & Bahan Bakar',
            'amount' => 5500000,
            'transaction_date' => now()->toDateString(),
            'reference_invoice' => 'REF-KEUANGAN-999-REV',
            'description' => 'Biaya BBM Truk Revisi Admin',
        ]);
        $editResponse->assertRedirect(route('admin.finance.index'));
        $this->assertDatabaseHas('transactions', [
            'id' => $trx->id,
            'amount' => 5500000,
            'reference_invoice' => 'REF-KEUANGAN-999-REV',
        ]);

        // Admin can delete the transaction
        $deleteResponse = $this->actingAs($admin)->delete("/admin/finance/{$trx->id}");
        $deleteResponse->assertRedirect(route('admin.finance.index'));
        $this->assertDatabaseMissing('transactions', ['id' => $trx->id]);
    }

    public function test_superadmin_user_management_crud()
    {
        $admin = User::where('email', 'admin@erickman.co.id')->first();

        // Admin visits user management
        $response = $this->actingAs($admin)->get('/admin/users');
        $response->assertStatus(200);
        $response->assertSee('Daftar Akun Pengguna');

        // Admin creates a new user
        $createResponse = $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Staf Operasional',
            'email' => 'ops@erickman.co.id',
            'password' => 'ops123456',
            'role' => 'keuangan',
            'phone' => '+62 813 9999 8888',
        ]);
        $createResponse->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['email' => 'ops@erickman.co.id']);

        $newUser = User::where('email', 'ops@erickman.co.id')->first();

        // Admin edits the user
        $updateResponse = $this->actingAs($admin)->put("/admin/users/{$newUser->id}", [
            'name' => 'Staf Operasional Senior',
            'email' => 'ops@erickman.co.id',
            'role' => 'keuangan',
            'phone' => '+62 813 9999 7777',
        ]);
        $updateResponse->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['name' => 'Staf Operasional Senior']);

        // Admin deletes the user
        $deleteResponse = $this->actingAs($admin)->delete("/admin/users/{$newUser->id}");
        $deleteResponse->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $newUser->id]);
    }
}

