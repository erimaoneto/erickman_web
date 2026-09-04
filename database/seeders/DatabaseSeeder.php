<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\SiteSetting;
use App\Models\Banner;
use App\Models\Service;
use App\Models\Fleet;
use App\Models\FleetMaintenance;
use App\Models\Transaction;
use App\Models\Inquiry;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin Default
        $admin = User::firstOrCreate(
            ['email' => 'admin@erickman.co.id'],
            [
                'name' => 'Administrator Erickman',
                'password' => Hash::make('admin12345'),
                'role' => 'superadmin',
                'phone' => '+62 811 8888 1234',
            ]
        );

        // 2. Site Settings
        $settings = [
            // Company Info
            ['key' => 'company_name', 'value' => 'PT. Erickman Sarana Abadi', 'group' => 'company', 'type' => 'text', 'label' => 'Nama Perusahaan'],
            ['key' => 'company_tagline', 'value' => 'Solusi Transportasi Gas Alam & Logistik Armada Khusus', 'group' => 'company', 'type' => 'text', 'label' => 'Tagline Perusahaan'],
            ['key' => 'company_description', 'value' => 'Penyedia terpercaya untuk solusi pengadaan dan transportasi gas alam (CNG/LNG), angkutan barang khusus, serta penyewaan armada truk berstandar keselamatan tinggi di Indonesia.', 'group' => 'company', 'type' => 'textarea', 'label' => 'Deskripsi Singkat'],
            ['key' => 'company_nib', 'value' => '2211210015706', 'group' => 'company', 'type' => 'text', 'label' => 'Nomor Induk Berusaha (NIB)'],
            
            // Contact & Address (From Authentic NIB Document)
            ['key' => 'company_address', 'value' => '18 Office Park Building, 12th Floor Unit A & H, Jl. TB Simatupang No.18, RT 002 RW 001, Kel. Kebagusan, Kec. Pasar Minggu, Kota Adm. Jakarta Selatan, DKI Jakarta 12520', 'group' => 'contact', 'type' => 'textarea', 'label' => 'Alamat Kantor Pusat'],
            ['key' => 'company_phone', 'value' => '+62 21 2278 1818', 'group' => 'contact', 'type' => 'text', 'label' => 'Nomor Telepon'],
            ['key' => 'company_email', 'value' => 'info@erickman.co.id', 'group' => 'contact', 'type' => 'text', 'label' => 'Email Resmi'],
            ['key' => 'company_whatsapp', 'value' => '+6281188881234', 'group' => 'contact', 'type' => 'text', 'label' => 'WhatsApp Hotline'],
            ['key' => 'operational_hours', 'value' => 'Senin - Sabtu: 08.00 - 17.00 WIB (Layanan Dispatch Armada 24/7)', 'group' => 'contact', 'type' => 'text', 'label' => 'Jam Operasional'],

            // About & Vision Mission
            ['key' => 'about_story', 'value' => 'Didirikan dengan komitmen kuat terhadap ketahanan energi dan efisiensi rantai pasok industri, PT Erickman beroperasi di bawah legalitas Perizinan Berusaha Berbasis Risiko (NIB 2211210015706) yang berpusat di 18 Office Park Simatupang Jakarta Selatan. Kami mengedepankan standar keselamatan tinggi (HSE/K3), armada modern berteknologi terkini, dan pengemudi tersertifikasi.', 'group' => 'about', 'type' => 'textarea', 'label' => 'Cerita Perusahaan'],
            ['key' => 'company_vision', 'value' => 'Menjadi perusahaan transportasi energi gas dan logistik armada khusus terdepan di Indonesia yang berorientasi pada kepuasan pelanggan, zero accident, dan efisiensi operasional prima.', 'group' => 'about', 'type' => 'textarea', 'label' => 'Visi'],
            ['key' => 'company_mission', 'value' => "1. Memberikan layanan distribusi gas alam dan logistik barang berstandar keselamatan (HSE) tertinggi.\n2. Mengoperasikan armada modern terawat dengan sistem pemantauan berkala.\n3. Menjadi mitra strategis rantai pasok energi bersih yang andal bagi industri nasional.\n4. Menjunjung kepatuhan regulasi pemerintah dan nilai profesionalisme tinggi.", 'group' => 'about', 'type' => 'textarea', 'label' => 'Misi'],
            
            // Statistics Counter
            ['key' => 'stat_fleet_count', 'value' => '50+', 'group' => 'stats', 'type' => 'text', 'label' => 'Unit Armada Aktif'],
            ['key' => 'stat_cng_delivered', 'value' => '1.500.000+', 'group' => 'stats', 'type' => 'text', 'label' => 'MMSCF Gas Terdistribusi'],
            ['key' => 'stat_ontime_rate', 'value' => '99.4%', 'group' => 'stats', 'type' => 'text', 'label' => 'Tingkat Ketepatan Waktu'],
            ['key' => 'stat_safety_record', 'value' => '100% Zero Accident', 'group' => 'stats', 'type' => 'text', 'label' => 'Rekor Keselamatan Kerja'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }

        // 3. Banners (Menggunakan Foto Truk Hijau CNG & Truk Merah Boks yang diunggah)
        Banner::truncate();
        Banner::create([
            'title' => 'Transportasi & Distribusi Gas Alam (CNG / LNG)',
            'tagline' => 'Solusi Pasokan Energi Andal & Ramah Lingkungan',
            'description' => 'Didukung armada prime mover tangguh dengan cradle tabung silinder CNG berstandar keselamatan internasional untuk suplai gas industri yang stabil tanpa henti.',
            'image_path' => '/images/truck-cng-green.jpg',
            'button_text' => 'Konsultasi Pengadaan Gas',
            'button_url' => '#kontak',
            'order' => 1,
            'is_active' => true,
        ]);

        Banner::create([
            'title' => 'Sewa Truk & Logistik Angkutan Barang Khusus',
            'tagline' => 'Armada Prima, Terawat, & Tepat Waktu',
            'description' => 'Menyediakan beragam pilihan truk boks dan bak terbuka dengan uji KIR rutin serta pemeliharaan terjadwal untuk menjamin keamanan kargo industri Anda.',
            'image_path' => '/images/truck-box-red.jpg',
            'button_text' => 'Pesan Layanan Armada',
            'button_url' => '#layanan',
            'order' => 2,
            'is_active' => true,
        ]);

        // 4. Services (Sesuai KBLI resmi NIB)
        Service::truncate();
        $services = [
            [
                'title' => 'Distribusi & Pengadaan Gas Alam (CNG / LNG)',
                'slug' => 'distribusi-pengadaan-gas-alam',
                'kbli_code' => 'KBLI 35201 & 35202',
                'icon' => 'flame',
                'image_path' => '/images/truck-cng-green.jpg',
                'short_description' => 'Layanan suplai dan transportasi gas alam terkompresi (CNG) untuk kebutuhan pabrik, hotel, rumah sakit, dan sentra industri.',
                'description' => 'Kami melayani rantai pengadaan dan distribusi gas alam terkompresi (CNG) dan gas cair (LNG). Dilengkapi armada trailer dengan tabung bertekanan tinggi yang terkalibrasi secara ketat dan diawasi oleh tim teknis profesional bersertifikasi Migas.',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Angkutan Bermotor untuk Barang Khusus & B3',
                'slug' => 'angkutan-barang-khusus',
                'kbli_code' => 'KBLI 49432',
                'icon' => 'shield-check',
                'image_path' => '/images/truck-cng-green.jpg',
                'short_description' => 'Pengangkutan kargo berisiko tinggi, tabung gas bertekanan, dan bahan bakar industri dengan standar SOP ketat.',
                'description' => 'Pelayanan angkutan barang berbahaya dan gas dengan pengemudi terlatih yang memiliki sertifikasi pengangkutan bahan berbahaya beracun serta armada yang dilengkapi peralatan APAR dan tanggap darurat.',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Aktivitas Penyewaan Truk & Armada Operasional',
                'slug' => 'penyewaan-truk-armada',
                'kbli_code' => 'KBLI 77100 & 77399',
                'icon' => 'truck',
                'image_path' => '/images/truck-box-red.jpg',
                'short_description' => 'Rental truk boks engkel, double, fuso, hingga prime mover untuk kontrak harian, bulanan, maupun tahunan.',
                'description' => 'Solusi sewa guna usaha armada komersial bagi korporasi dan logistik B2B. Semua unit mendapatkan servis rutin, asuransi komprehensif, dan opsi layanan pengemudi berpengalaman.',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Angkutan Bermotor untuk Barang Umum',
                'slug' => 'angkutan-barang-umum',
                'kbli_code' => 'KBLI 49431',
                'icon' => 'package',
                'image_path' => '/images/truck-box-red.jpg',
                'short_description' => 'Distribusi barang komersial, FMCG, suku cadang, dan bahan baku manufaktur antarkota dan antarpulau.',
                'description' => 'Jasa ekspedisi dan pengiriman barang umum skala besar ke berbagai destinasi strategis di pulau Jawa dan sekitarnya dengan jaminan keamanan muatan hingga titik tujuan.',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Perdagangan Besar Bahan Bakar & Energi',
                'slug' => 'perdagangan-besar-bahan-bakar',
                'kbli_code' => 'KBLI 46610 & 46900',
                'icon' => 'fuel',
                'image_path' => '/images/truck-cng-green.jpg',
                'short_description' => 'Penyediaan komersial bahan bakar padat, cair, dan gas untuk sektor industri dan manufaktur berskala besar.',
                'description' => 'Perdagangan besar resmi untuk kebutuhan energi industri dengan skema kontrak terpercaya, kualitas teruji di laboratorium independen, dan pengiriman tepat jadwal.',
                'order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        // 5. Fleets (Armada Realistis Sesuai Dokumen & Foto)
        Fleet::truncate();
        $fleet1 = Fleet::create([
            'plate_number' => 'B 9108 UEM',
            'vehicle_name' => 'Prime Mover CNG Cradle 01',
            'type' => 'CNG Tube Trailer',
            'brand' => 'UD Quester GWE 280',
            'year' => '2022',
            'capacity' => '2.500 m³ CNG (250 Bar)',
            'status' => 'Tersedia',
            'driver_name' => 'Bambang Supriyanto',
            'kir_expiry' => Carbon::now()->addMonths(5),
            'stnk_expiry' => Carbon::now()->addMonths(9),
            'image_path' => '/images/truck-cng-green.jpg',
            'notes' => 'Armada utama untuk rute stasiun pengisian CNG ke pabrik Cilegon - Cikarang.',
        ]);

        $fleet2 = Fleet::create([
            'plate_number' => 'T 8434 DY',
            'vehicle_name' => 'Toyota Dyna Box Logistics 02',
            'type' => 'Box Truck',
            'brand' => 'Toyota Dyna 110 FY',
            'year' => '2021',
            'capacity' => '8.5 Ton / 22 m³',
            'status' => 'Dalam Perjalanan',
            'driver_name' => 'Agus Hendrawan',
            'kir_expiry' => Carbon::now()->addDays(25), // Uji KIR segera jatuh tempo!
            'stnk_expiry' => Carbon::now()->addMonths(7),
            'image_path' => '/images/truck-box-red.jpg',
            'notes' => 'Sedang bertugas pengiriman muatan ke kawasan industri Karawang.',
        ]);

        $fleet3 = Fleet::create([
            'plate_number' => 'B 9482 TYN',
            'vehicle_name' => 'Hino Ranger CNG Transport 03',
            'type' => 'CNG Tube Trailer',
            'brand' => 'Hino Ranger FM 260 Ti',
            'year' => '2020',
            'capacity' => '2.200 m³ CNG',
            'status' => 'Tersedia',
            'driver_name' => 'Heri Setiawan',
            'kir_expiry' => Carbon::now()->addMonths(3),
            'stnk_expiry' => Carbon::now()->addMonths(11),
            'image_path' => '/images/truck-cng-green.jpg',
            'notes' => 'Unit standby di pool TB Simatupang.',
        ]);

        $fleet4 = Fleet::create([
            'plate_number' => 'B 9312 KBC',
            'vehicle_name' => 'Isuzu Giga Heavy Box 04',
            'type' => 'Heavy Box Truck',
            'brand' => 'Isuzu Giga FVM 34 U',
            'year' => '2019',
            'capacity' => '15 Ton / 42 m³',
            'status' => 'Perawatan',
            'driver_name' => 'Deddy Pratama',
            'kir_expiry' => Carbon::now()->subDays(5), // Kedaluwarsa
            'stnk_expiry' => Carbon::now()->addMonths(4),
            'image_path' => '/images/truck-box-red.jpg',
            'notes' => 'Sedang rekondisi sistem rem dan persiapan uji berkala KIR di bengkel.',
        ]);

        // 6. Fleet Maintenances
        FleetMaintenance::truncate();
        FleetMaintenance::create([
            'fleet_id' => $fleet1->id,
            'service_date' => Carbon::now()->subDays(15),
            'service_type' => 'Kalibrasi Pressure Manometer & Safety Valve CNG',
            'cost' => 4500000,
            'workshop' => 'Bengkel Sertifikasi Migas Cilegon',
            'odometer_km' => 48200,
            'description' => 'Pemeriksaan rutin manifold pipa dan 12 tabung gas silinder cradle, semua tekanan normal 250 bar.',
        ]);

        FleetMaintenance::create([
            'fleet_id' => $fleet2->id,
            'service_date' => Carbon::now()->subDays(30),
            'service_type' => 'Ganti Oli Mesin & Filter Solar',
            'cost' => 1750000,
            'workshop' => 'Auto2000 Karawang',
            'odometer_km' => 74500,
            'description' => 'Servis berkala 75.000 km, ganti kanvas kopling dan pengecekan suspensi belakang.',
        ]);

        FleetMaintenance::create([
            'fleet_id' => $fleet4->id,
            'service_date' => Carbon::now()->subDays(2),
            'service_type' => 'Pemeriksaan Sistem Rem & Persiapan Uji KIR',
            'cost' => 2800000,
            'workshop' => 'Bengkel Mitra Armada Cikarang',
            'odometer_km' => 112000,
            'description' => 'Ganti booster rem dan peremajaan lampu sein belakang untuk uji KIR.',
        ]);

        // 7. Transactions (Keuangan Realistis)
        Transaction::truncate();
        Transaction::create([
            'code' => 'TRX-' . Carbon::now()->format('Ym') . '-001',
            'type' => 'pemasukan',
            'category' => 'Distribusi Gas CNG',
            'amount' => 54000000,
            'transaction_date' => Carbon::now()->subDays(18),
            'fleet_id' => $fleet1->id,
            'reference_invoice' => 'INV-ERK-260801',
            'description' => 'Pembayaran Kontrak Pengiriman CNG Periode Agustus (PT Krakatau Industri Energi)',
            'created_by' => $admin->id,
        ]);

        Transaction::create([
            'code' => 'TRX-' . Carbon::now()->format('Ym') . '-002',
            'type' => 'pemasukan',
            'category' => 'Sewa Truk',
            'amount' => 22500000,
            'transaction_date' => Carbon::now()->subDays(10),
            'fleet_id' => $fleet2->id,
            'reference_invoice' => 'INV-ERK-260814',
            'description' => 'Sewa Bulanan Unit Dyna Box PT Global Logistik Nusantara',
            'created_by' => $admin->id,
        ]);

        Transaction::create([
            'code' => 'TRX-' . Carbon::now()->format('Ym') . '-003',
            'type' => 'pengeluaran',
            'category' => 'BBM & Bahan Bakar',
            'amount' => 14200000,
            'transaction_date' => Carbon::now()->subDays(8),
            'fleet_id' => $fleet1->id,
            'reference_invoice' => 'SPBU-9021',
            'description' => 'Pengisian Gas Mother Station dan Solar armada CNG trailer rute Banten',
            'created_by' => $admin->id,
        ]);

        Transaction::create([
            'code' => 'TRX-' . Carbon::now()->format('Ym') . '-004',
            'type' => 'pengeluaran',
            'category' => 'Uang Jalan Supir',
            'amount' => 4500000,
            'transaction_date' => Carbon::now()->subDays(6),
            'fleet_id' => $fleet2->id,
            'reference_invoice' => 'UJ-260822',
            'description' => 'Uang jalan, konsumsi, dan tol rute Jabodetabek - Jawa Barat',
            'created_by' => $admin->id,
        ]);

        Transaction::create([
            'code' => 'TRX-' . Carbon::now()->format('Ym') . '-005',
            'type' => 'pengeluaran',
            'category' => 'Maintenance Armada',
            'amount' => 4500000,
            'transaction_date' => Carbon::now()->subDays(15),
            'fleet_id' => $fleet1->id,
            'reference_invoice' => 'SRV-0912',
            'description' => 'Kalibrasi Pressure Manometer & Safety Valve CNG',
            'created_by' => $admin->id,
        ]);

        Transaction::create([
            'code' => 'TRX-' . Carbon::now()->format('Ym') . '-006',
            'type' => 'pemasukan',
            'category' => 'Distribusi Gas CNG',
            'amount' => 62000000,
            'transaction_date' => Carbon::now()->subDays(3),
            'fleet_id' => $fleet3->id,
            'reference_invoice' => 'INV-ERK-260829',
            'description' => 'Suplai Gas Alam Pabrik Keramik Karawang Tahap 1',
            'created_by' => $admin->id,
        ]);

        // 8. Inquiries (Contoh Pesan Masuk dari Form Front-end)
        Inquiry::truncate();
        Inquiry::create([
            'name' => 'Ir. Hendra Gunawan',
            'company' => 'PT Karawang Sentra Industri',
            'email' => 'hendra.gunawan@karawang-industri.com',
            'phone' => '081234567890',
            'service_interest' => 'Distribusi & Pengadaan Gas Alam (CNG / LNG)',
            'subject' => 'Permintaan Penawaran Suplai Gas Alam CNG 15.000 m3/Bulan',
            'message' => 'Selamat siang PT Erickman, kami memerlukan pasokan gas CNG rutin untuk operasional boiler pabrik kami di Karawang Barat. Mohon info harga per meter kubik dan kesiapan armada pengantaran.',
            'is_read' => false,
        ]);

        Inquiry::create([
            'name' => 'Siti Rahmawati',
            'company' => 'CV Multi Distribusi Mandiri',
            'email' => 'siti@multidistribusi.co.id',
            'phone' => '081398765432',
            'service_interest' => 'Aktivitas Penyewaan Truk & Armada Operasional',
            'subject' => 'Sewa Truk Boks Dyna Kontrak 6 Bulan',
            'message' => 'Halo tim Erickman, kami ingin menanyakan ketersediaan 2 unit truk boks sekelas Toyota Dyna untuk distribusi ritel Jabodetabek dengan kontrak sewa 6 bulan. Mohon kirimkan skema penawaran resmi.',
            'is_read' => true,
            'replied_at' => Carbon::now()->subDay(),
        ]);
    }
}
