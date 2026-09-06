<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
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
        Schema::disableForeignKeyConstraints();

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

        // 2. Akun Keuangan Default
        $keuangan = User::updateOrCreate(
            ['email' => 'keuangan@erickman.co.id'],
            [
                'name' => 'Staf Keuangan Erickman',
                'password' => Hash::make('keuangan123'),
                'role' => 'keuangan',
                'phone' => '+62 812 8888 5678',
            ]
        );

        // 2. Site Settings
        $settings = [
            // Company Info
            ['key' => 'company_name', 'value' => 'PT. Erickman Sarana Abadi', 'group' => 'company', 'type' => 'text', 'label' => 'Nama Perusahaan'],
            ['key' => 'company_tagline', 'value' => 'Oil, Gas, & Transportation', 'group' => 'company', 'type' => 'text', 'label' => 'Tagline Perusahaan'],
            ['key' => 'company_description', 'value' => 'Perusahaan yang berkedudukan dan berkantor pusat di kota Jakarta, bergerak di bidang pendistribusian gas LPG (Liquified Petroleum Gas) dan CNG (Compressed Natural Gas), juga penyedia sarana transportasi migas dan batu bara (darat & laut).', 'group' => 'company', 'type' => 'textarea', 'label' => 'Deskripsi Singkat'],
            ['key' => 'company_nib', 'value' => '2211210015706', 'group' => 'company', 'type' => 'text', 'label' => 'Nomor Induk Berusaha (NIB)'],
            
            // Contact & Address (Official Company Profile PDF)
            ['key' => 'company_address', 'value' => '18 Office Park 21st Floor, Jl. TB Simatupang Kav. 18, RT 002 RW 001, Kel. Kebagusan, Kec. Pasar Minggu, Kota Adm. Jakarta Selatan, DKI Jakarta 12520', 'group' => 'contact', 'type' => 'textarea', 'label' => 'Alamat Kantor Pusat'],
            ['key' => 'company_phone', 'value' => '+62 21 2278 1818', 'group' => 'contact', 'type' => 'text', 'label' => 'Nomor Telepon'],
            ['key' => 'company_email', 'value' => 'info@erickman.co.id', 'group' => 'contact', 'type' => 'text', 'label' => 'Email Resmi'],
            ['key' => 'company_whatsapp', 'value' => '+6281188881234', 'group' => 'contact', 'type' => 'text', 'label' => 'WhatsApp Hotline'],
            ['key' => 'operational_hours', 'value' => 'Senin - Sabtu: 08.00 - 17.00 WIB (Layanan Dispatch Armada 24/7)', 'group' => 'contact', 'type' => 'text', 'label' => 'Jam Operasional'],

            // About & Vision Mission (Official Company Profile PDF)
            ['key' => 'about_story', 'value' => 'PT. Erickman Sarana Abadi berkedudukan dan berkantor pusat di kota Jakarta (18 Office Park 21st Floor) adalah perusahaan yang bergerak di bidang pendistribusian gas LPG (distributor resmi Gas LPG merk HARIGAS) dan CNG (Compressed Natural Gas), juga penyedia sarana transportasi migas (LPG, CNG, Crude Oil) & Batu Bara, baik jalur darat maupun laut.', 'group' => 'about', 'type' => 'textarea', 'label' => 'Cerita Perusahaan'],
            ['key' => 'company_vision', 'value' => 'Menjadi perusahaan distribusi energi migas dan penyedia transportasi logistik terdepan di Indonesia yang andal, berstandar keselamatan prima, dan berorientasi pada kepuasan pelanggan.', 'group' => 'about', 'type' => 'textarea', 'label' => 'Visi'],
            ['key' => 'company_mission', 'value' => "1. Memberikan layanan distribusi gas LPG (HARIGAS) dan gas CNG berkualitas tinggi untuk industri dan retail di area Jabodetabek dan Jawa Barat.\n2. Menyediakan sarana transportasi migas (LPG, CNG, Crude Oil) dan batu bara jalur darat dan laut yang andal dan tepat waktu.\n3. Mengoperasikan armada modern dengan standar keselamatan HSE (K3) migas tertinggi.\n4. Menjadi mitra strategis energi terpercaya bagi sektor industri nasional.", 'group' => 'about', 'type' => 'textarea', 'label' => 'Misi'],
            
            // Statistics Counter
            ['key' => 'stat_fleet_count', 'value' => '50+', 'group' => 'stats', 'type' => 'text', 'label' => 'Unit Armada Aktif'],
            ['key' => 'stat_cng_delivered', 'value' => '1.500.000+', 'group' => 'stats', 'type' => 'text', 'label' => 'MMSCF Gas Terdistribusi'],
            ['key' => 'stat_ontime_rate', 'value' => '99.4%', 'group' => 'stats', 'type' => 'text', 'label' => 'Tingkat Ketepatan Waktu'],
            ['key' => 'stat_safety_record', 'value' => '100% Zero Accident', 'group' => 'stats', 'type' => 'text', 'label' => 'Rekor Keselamatan Kerja'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }

        // 3. Banners
        Banner::truncate();
        Banner::create([
            'title' => 'PT. Erickman Sarana Abadi',
            'tagline' => 'Oil, Gas, & Transportation',
            'description' => 'Pendistribusian gas LPG (Distributor Resmi HARIGAS), pengadaan gas CNG, serta penyedia sarana transportasi migas & batu bara jalur darat dan laut.',
            'image_path' => '/images/refinery-migas.jpg',
            'button_text' => 'Lihat Layanan Kami',
            'button_url' => '#layanan',
            'order' => 1,
            'is_active' => true,
        ]);

        Banner::create([
            'title' => 'Distribusi Gas LPG (HARIGAS) & CNG Industri',
            'tagline' => 'Solusi Pasokan Energi Andal JABODETABEK & Jawa Barat',
            'description' => 'Distributor resmi Gas LPG merk HARIGAS untuk kebutuhan industri & retail serta pengadaan peralatan dan Gas CNG berkualitas tinggi.',
            'image_path' => '/images/service-lpg-harigas.jpg',
            'button_text' => 'Pelajari Layanan Gas',
            'button_url' => '#layanan',
            'order' => 2,
            'is_active' => true,
        ]);

        Banner::create([
            'title' => 'Transportasi Migas & Batu Bara (Darat & Laut)',
            'tagline' => 'Distribusi LPG, CNG, Crude Oil, & Batu Bara',
            'description' => 'Layanan transportasi untuk keperluan distribusi migas dan batu bara, baik jalur darat dengan truk tangki dan prime mover, maupun jalur laut dengan armada kapal tongkang.',
            'image_path' => '/images/service-transportasi-cng.jpg',
            'button_text' => 'Hubungi Kami',
            'button_url' => '#kontak',
            'order' => 3,
            'is_active' => true,
        ]);

        // 4. Services (Sesuai Company Profile PDF)
        Service::truncate();
        $services = [
            [
                'title' => 'LPG (Liquified Petroleum Gas)',
                'slug' => 'lpg-liquified-petroleum-gas',
                'kbli_code' => 'Distributor Resmi HARIGAS',
                'icon' => 'flame',
                'image_path' => '/images/service-lpg-harigas.jpg',
                'short_description' => 'Distributor resmi yang memberikan layanan pengadaan Gas LPG merk HARIGAS untuk berbagai kebutuhan industri dan Retail di area JABODETABEK dan Jawa Barat.',
                'description' => 'PT. Erickman Sarana Abadi merupakan distributor resmi yang memberikan layanan pengadaan Gas LPG merk HARIGAS untuk berbagai kebutuhan industri dan Retail di area JABODETABEK dan Jawa Barat. Didukung manajemen rantai pasok tabung berkualitas, pengiriman tepat waktu, serta standar keselamatan penyimpanan dan pengisian.',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'CNG (Compressed Natural Gas)',
                'slug' => 'cng-compressed-natural-gas',
                'kbli_code' => 'Pengadaan Peralatan & Gas CNG',
                'icon' => 'gauge-high',
                'image_path' => '/images/service-cng-trailer.jpg',
                'short_description' => 'Layanan pengadaan peralatan dan Gas CNG untuk berbagai kebutuhan industri dan Retail di area JABODETABEK dan Jawa Barat.',
                'description' => 'PT. Erickman Sarana Abadi memberikan layanan pengadaan peralatan dan Gas CNG untuk berbagai kebutuhan industri dan Retail di area JABODETABEK dan Jawa Barat. Solusi energi ramah lingkungan dengan efisiensi tinggi bagi pabrik manufaktur, hotel, rumah sakit, dan sentra komersial.',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Transportasi Migas & Batu Bara',
                'slug' => 'transportasi-migas-dan-batu-bara',
                'kbli_code' => 'Distribusi Jalur Darat & Laut',
                'icon' => 'truck-fast',
                'image_path' => '/images/service-transportasi-cng.jpg',
                'short_description' => 'Layanan transportasi untuk keperluan distribusi migas (LPG, CNG, Crude Oil) & Batu Bara, baik jalur darat maupun laut.',
                'description' => 'PT. Erickman Sarana Abadi memberikan layanan transportasi untuk keperluan distribusi migas (LPG, CNG, Crude Oil) & Batu Bara, baik jalur darat (truk tangki, prime mover trailer) maupun jalur laut (kapal tongkang/barge) dengan standar HSE dan perizinan resmi.',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Penyewaan Truk & Armada Komersial',
                'slug' => 'penyewaan-truk-armada',
                'kbli_code' => 'KBLI 77100 & 77399',
                'icon' => 'truck',
                'image_path' => '/images/truck-box-red.jpg',
                'short_description' => 'Rental truk boks, armada khusus, dan prime mover berstandar keselamatan tinggi untuk kontrak korporasi.',
                'description' => 'Solusi sewa guna armada komersial bagi korporasi dan logistik B2B. Semua unit mendapatkan servis rutin, asuransi komprehensif, dan opsi layanan pengemudi berpengalaman.',
                'order' => 4,
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
                'order' => 5,
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
                'order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        // 5. Fleets (Armada Realistis Sesuai Dokumen & Foto)
        FleetMaintenance::truncate();
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

        Schema::enableForeignKeyConstraints();
    }
}
