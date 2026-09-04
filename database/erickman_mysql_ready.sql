-- ==========================================================
-- PT ERICKMAN (erickman.co.id)
-- MySQL Database Dump Siap Import untuk Hostinger hPanel
-- ==========================================================

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Table structure for `users`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `role` varchar(50) NOT NULL DEFAULT 'admin',
  `phone` varchar(50) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin Default: admin@erickman.co.id / admin12345
INSERT INTO `users` (`id`, `name`, `email`, `role`, `phone`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Administrator Erickman', 'admin@erickman.co.id', 'superadmin', '+62 811 8888 1234', '$2y$12$2jGqN4jP/lJkMqm/5yXJ8u8Uv3u66cI1G1vX6/8w/Xz5hV1dG9Gqm', NOW(), NOW());

-- --------------------------------------------------------
-- Table structure for `site_settings`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `site_settings`;
CREATE TABLE `site_settings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL UNIQUE,
  `value` text DEFAULT NULL,
  `group` varchar(50) NOT NULL DEFAULT 'general',
  `type` varchar(50) NOT NULL DEFAULT 'text',
  `label` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `site_settings` (`key`, `value`, `group`, `type`, `label`, `created_at`, `updated_at`) VALUES
('company_name', 'PT. Erickman Sarana Abadi', 'company', 'text', 'Nama Perusahaan', NOW(), NOW()),
('company_tagline', 'Solusi Transportasi Gas Alam & Logistik Armada Khusus', 'company', 'text', 'Tagline Perusahaan', NOW(), NOW()),
('company_description', 'Penyedia terpercaya untuk solusi pengadaan dan transportasi gas alam (CNG/LNG), angkutan barang khusus, serta penyewaan armada truk berstandar keselamatan tinggi di Indonesia.', 'company', 'textarea', 'Deskripsi Singkat', NOW(), NOW()),
('company_nib', '2211210015706', 'company', 'text', 'Nomor Induk Berusaha (NIB)', NOW(), NOW()),
('company_address', '18 Office Park Building, 12th Floor Unit A & H, Jl. TB Simatupang No.18, RT 002 RW 001, Kel. Kebagusan, Kec. Pasar Minggu, Kota Adm. Jakarta Selatan, DKI Jakarta 12520', 'contact', 'textarea', 'Alamat Kantor Pusat', NOW(), NOW()),
('company_phone', '+62 21 2278 1818', 'contact', 'text', 'Nomor Telepon', NOW(), NOW()),
('company_email', 'info@erickman.co.id', 'contact', 'text', 'Email Resmi', NOW(), NOW()),
('company_whatsapp', '+6281188881234', 'contact', 'text', 'WhatsApp Hotline', NOW(), NOW()),
('operational_hours', 'Senin - Sabtu: 08.00 - 17.00 WIB (Layanan Dispatch Armada 24/7)', 'contact', 'text', 'Jam Operasional', NOW(), NOW()),
('about_story', 'Didirikan dengan komitmen kuat terhadap ketahanan energi dan efisiensi rantai pasok industri, PT Erickman beroperasi di bawah legalitas Perizinan Berusaha Berbasis Risiko (NIB 2211210015706) yang berpusat di 18 Office Park Simatupang Jakarta Selatan. Kami mengedepankan standar keselamatan tinggi (HSE/K3), armada modern berteknologi terkini, dan pengemudi tersertifikasi.', 'about', 'textarea', 'Cerita Perusahaan', NOW(), NOW()),
('company_vision', 'Menjadi perusahaan transportasi energi gas dan logistik armada khusus terdepan di Indonesia yang berorientasi pada kepuasan pelanggan, zero accident, dan efisiensi operasional prima.', 'about', 'textarea', 'Visi', NOW(), NOW()),
('company_mission', '1. Memberikan layanan distribusi gas alam dan logistik barang berstandar keselamatan (HSE) tertinggi.\n2. Mengoperasikan armada modern terawat dengan sistem pemantauan berkala.\n3. Menjadi mitra strategis rantai pasok energi bersih yang andal bagi industri nasional.\n4. Menjunjung kepatuhan regulasi pemerintah dan nilai profesionalisme tinggi.', 'about', 'textarea', 'Misi', NOW(), NOW()),
('stat_fleet_count', '50+', 'stats', 'text', 'Unit Armada Aktif', NOW(), NOW()),
('stat_cng_delivered', '1.500.000+', 'stats', 'text', 'MMSCF Gas Terdistribusi', NOW(), NOW()),
('stat_ontime_rate', '99.4%', 'stats', 'text', 'Tingkat Ketepatan Waktu', NOW(), NOW()),
('stat_safety_record', '100% Zero Accident', 'stats', 'text', 'Rekor Keselamatan Kerja', NOW(), NOW());

-- --------------------------------------------------------
-- Table structure for `banners`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `banners`;
CREATE TABLE `banners` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `tagline` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `button_text` varchar(100) DEFAULT NULL,
  `button_url` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `banners` (`id`, `title`, `tagline`, `description`, `image_path`, `button_text`, `button_url`, `order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Transportasi & Distribusi Gas Alam (CNG / LNG)', 'Solusi Pasokan Energi Andal & Ramah Lingkungan', 'Didukung armada prime mover tangguh dengan cradle tabung silinder CNG berstandar keselamatan internasional untuk suplai gas industri yang stabil tanpa henti.', '/images/truck-cng-green.jpg', 'Konsultasi Pengadaan Gas', '#kontak', 1, 1, NOW(), NOW()),
(2, 'Sewa Truk & Logistik Angkutan Barang Khusus', 'Armada Prima, Terawat, & Tepat Waktu', 'Menyediakan beragam pilihan truk boks dan bak terbuka dengan uji KIR rutin serta pemeliharaan terjadwal untuk menjamin keamanan kargo industri Anda.', '/images/truck-box-red.jpg', 'Pesan Layanan Armada', '#layanan', 2, 1, NOW(), NOW());

-- --------------------------------------------------------
-- Table structure for `services`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `services`;
CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL UNIQUE,
  `kbli_code` varchar(100) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `short_description` text NOT NULL,
  `description` longtext DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `services` (`id`, `title`, `slug`, `kbli_code`, `icon`, `image_path`, `short_description`, `description`, `order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Distribusi & Pengadaan Gas Alam (CNG / LNG)', 'distribusi-pengadaan-gas-alam', 'KBLI 35201 & 35202', 'flame', '/images/truck-cng-green.jpg', 'Layanan suplai dan transportasi gas alam terkompresi (CNG) untuk kebutuhan pabrik, hotel, rumah sakit, dan sentra industri.', 'Kami melayani rantai pengadaan dan distribusi gas alam terkompresi (CNG) dan gas cair (LNG). Dilengkapi armada trailer dengan tabung bertekanan tinggi yang terkalibrasi secara ketat dan diawasi oleh tim teknis profesional bersertifikasi Migas.', 1, 1, NOW(), NOW()),
(2, 'Angkutan Bermotor untuk Barang Khusus & B3', 'angkutan-barang-khusus', 'KBLI 49432', 'shield-check', '/images/truck-cng-green.jpg', 'Pengangkutan kargo berisiko tinggi, tabung gas bertekanan, dan bahan bakar industri dengan standar SOP ketat.', 'Pelayanan angkutan barang berbahaya dan gas dengan pengemudi terlatih yang memiliki sertifikasi pengangkutan bahan berbahaya beracun serta armada yang dilengkapi peralatan APAR dan tanggap darurat.', 2, 1, NOW(), NOW()),
(3, 'Aktivitas Penyewaan Truk & Armada Operasional', 'penyewaan-truk-armada', 'KBLI 77100 & 77399', 'truck', '/images/truck-box-red.jpg', 'Rental truk boks engkel, double, fuso, hingga prime mover untuk kontrak harian, bulanan, maupun tahunan.', 'Solusi sewa guna usaha armada komersial bagi korporasi dan logistik B2B. Semua unit mendapatkan servis rutin, asuransi komprehensif, dan opsi layanan pengemudi berpengalaman.', 3, 1, NOW(), NOW()),
(4, 'Angkutan Bermotor untuk Barang Umum', 'angkutan-barang-umum', 'KBLI 49431', 'package', '/images/truck-box-red.jpg', 'Distribusi barang komersial, FMCG, suku cadang, dan bahan baku manufaktur antarkota dan antarpulau.', 'Jasa ekspedisi dan pengiriman barang umum skala besar ke berbagai destinasi strategis di pulau Jawa dan sekitarnya dengan jaminan keamanan muatan hingga titik tujuan.', 4, 1, NOW(), NOW()),
(5, 'Perdagangan Besar Bahan Bakar & Energi', 'perdagangan-besar-bahan-bakar', 'KBLI 46610 & 46900', 'fuel', '/images/truck-cng-green.jpg', 'Penyediaan komersial bahan bakar padat, cair, dan gas untuk sektor industri dan manufaktur berskala besar.', 'Perdagangan besar resmi untuk kebutuhan energi industri dengan skema kontrak terpercaya, kualitas teruji di laboratorium independen, dan pengiriman tepat jadwal.', 5, 1, NOW(), NOW());

-- --------------------------------------------------------
-- Table structure for `fleets`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `fleets`;
CREATE TABLE `fleets` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `plate_number` varchar(50) NOT NULL UNIQUE,
  `vehicle_name` varchar(150) NOT NULL,
  `type` varchar(100) NOT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `year` varchar(10) DEFAULT NULL,
  `capacity` varchar(100) DEFAULT NULL,
  `status` enum('Tersedia','Dalam Perjalanan','Perawatan','Non-Aktif') NOT NULL DEFAULT 'Tersedia',
  `driver_name` varchar(150) DEFAULT NULL,
  `kir_expiry` date DEFAULT NULL,
  `stnk_expiry` date DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `fleets` (`id`, `plate_number`, `vehicle_name`, `type`, `brand`, `year`, `capacity`, `status`, `driver_name`, `kir_expiry`, `stnk_expiry`, `image_path`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'B 9108 UEM', 'Prime Mover CNG Cradle 01', 'CNG Tube Trailer', 'UD Quester GWE 280', '2022', '2.500 m³ CNG (250 Bar)', 'Tersedia', 'Bambang Supriyanto', DATE_ADD(CURDATE(), INTERVAL 5 MONTH), DATE_ADD(CURDATE(), INTERVAL 9 MONTH), '/images/truck-cng-green.jpg', 'Armada utama untuk rute stasiun pengisian CNG ke pabrik Cilegon - Cikarang.', NOW(), NOW()),
(2, 'T 8434 DY', 'Toyota Dyna Box Logistics 02', 'Box Truck', 'Toyota Dyna 110 FY', '2021', '8.5 Ton / 22 m³', 'Dalam Perjalanan', 'Agus Hendrawan', DATE_ADD(CURDATE(), INTERVAL 25 DAY), DATE_ADD(CURDATE(), INTERVAL 7 MONTH), '/images/truck-box-red.jpg', 'Sedang bertugas pengiriman muatan ke kawasan industri Karawang.', NOW(), NOW()),
(3, 'B 9482 TYN', 'Hino Ranger CNG Transport 03', 'CNG Tube Trailer', 'Hino Ranger FM 260 Ti', '2020', '2.200 m³ CNG', 'Tersedia', 'Heri Setiawan', DATE_ADD(CURDATE(), INTERVAL 3 MONTH), DATE_ADD(CURDATE(), INTERVAL 11 MONTH), '/images/truck-cng-green.jpg', 'Unit standby di pool TB Simatupang.', NOW(), NOW()),
(4, 'B 9312 KBC', 'Isuzu Giga Heavy Box 04', 'Heavy Box Truck', 'Isuzu Giga FVM 34 U', '2019', '15 Ton / 42 m³', 'Perawatan', 'Deddy Pratama', DATE_SUB(CURDATE(), INTERVAL 5 DAY), DATE_ADD(CURDATE(), INTERVAL 4 MONTH), '/images/truck-box-red.jpg', 'Sedang rekondisi sistem rem dan persiapan uji berkala KIR di bengkel.', NOW(), NOW());

-- --------------------------------------------------------
-- Table structure for `fleet_maintenances`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `fleet_maintenances`;
CREATE TABLE `fleet_maintenances` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fleet_id` bigint(20) UNSIGNED NOT NULL,
  `service_date` date NOT NULL,
  `service_type` varchar(255) NOT NULL,
  `cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `workshop` varchar(150) DEFAULT NULL,
  `odometer_km` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`fleet_id`) REFERENCES `fleets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `fleet_maintenances` (`fleet_id`, `service_date`, `service_type`, `cost`, `workshop`, `odometer_km`, `description`, `created_at`, `updated_at`) VALUES
(1, DATE_SUB(CURDATE(), INTERVAL 15 DAY), 'Kalibrasi Pressure Manometer & Safety Valve CNG', 4500000.00, 'Bengkel Sertifikasi Migas Cilegon', 48200, 'Pemeriksaan rutin manifold pipa dan 12 tabung gas silinder cradle, semua tekanan normal 250 bar.', NOW(), NOW()),
(2, DATE_SUB(CURDATE(), INTERVAL 30 DAY), 'Ganti Oli Mesin & Filter Solar', 1750000.00, 'Auto2000 Karawang', 74500, 'Servis berkala 75.000 km, ganti kanvas kopling dan pengecekan suspensi belakang.', NOW(), NOW());

-- --------------------------------------------------------
-- Table structure for `transactions`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL UNIQUE,
  `type` enum('pemasukan','pengeluaran') NOT NULL,
  `category` varchar(100) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `transaction_date` date NOT NULL,
  `fleet_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reference_invoice` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `receipt_file` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`fleet_id`) REFERENCES `fleets` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `transactions` (`code`, `type`, `category`, `amount`, `transaction_date`, `fleet_id`, `reference_invoice`, `description`, `created_by`, `created_at`, `updated_at`) VALUES
('TRX-2026-001', 'pemasukan', 'Distribusi Gas CNG', 54000000.00, DATE_SUB(CURDATE(), INTERVAL 18 DAY), 1, 'INV-ERK-260801', 'Pembayaran Kontrak Pengiriman CNG Periode Agustus (PT Krakatau Industri Energi)', 1, NOW(), NOW()),
('TRX-2026-002', 'pemasukan', 'Sewa Truk', 22500000.00, DATE_SUB(CURDATE(), INTERVAL 10 DAY), 2, 'INV-ERK-260814', 'Sewa Bulanan Unit Dyna Box PT Global Logistik Nusantara', 1, NOW(), NOW()),
('TRX-2026-003', 'pengeluaran', 'BBM & Bahan Bakar', 14200000.00, DATE_SUB(CURDATE(), INTERVAL 8 DAY), 1, 'SPBU-9021', 'Pengisian Gas Mother Station dan Solar armada CNG trailer rute Banten', 1, NOW(), NOW()),
('TRX-2026-004', 'pengeluaran', 'Uang Jalan Supir', 4500000.00, DATE_SUB(CURDATE(), INTERVAL 6 DAY), 2, 'UJ-260822', 'Uang jalan, konsumsi, dan tol rute Jabodetabek - Jawa Barat', 1, NOW(), NOW()),
('TRX-2026-005', 'pengeluaran', 'Maintenance Armada', 4500000.00, DATE_SUB(CURDATE(), INTERVAL 15 DAY), 1, 'SRV-0912', 'Kalibrasi Pressure Manometer & Safety Valve CNG', 1, NOW(), NOW());

-- --------------------------------------------------------
-- Table structure for `inquiries`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `inquiries`;
CREATE TABLE `inquiries` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `company` varchar(150) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `service_interest` varchar(150) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `replied_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `inquiries` (`id`, `name`, `company`, `email`, `phone`, `service_interest`, `subject`, `message`, `is_read`, `replied_at`, `created_at`, `updated_at`) VALUES
(1, 'Ir. Hendra Gunawan', 'PT Karawang Sentra Industri', 'hendra.gunawan@karawang-industri.com', '081234567890', 'Distribusi & Pengadaan Gas Alam (CNG / LNG)', 'Permintaan Penawaran Suplai Gas Alam CNG 15.000 m3/Bulan', 'Selamat siang PT Erickman, kami memerlukan pasokan gas CNG rutin untuk operasional boiler pabrik kami di Karawang Barat. Mohon info harga per meter kubik dan kesiapan armada pengantaran.', 0, NULL, NOW(), NOW()),
(2, 'Siti Rahmawati', 'CV Multi Distribusi Mandiri', 'siti@multidistribusi.co.id', '081398765432', 'Aktivitas Penyewaan Truk & Armada Operasional', 'Sewa Truk Boks Dyna Kontrak 6 Bulan', 'Halo tim Erickman, kami ingin menanyakan ketersediaan 2 unit truk boks sekelas Toyota Dyna untuk distribusi ritel Jabodetabek dengan kontrak sewa 6 bulan. Mohon kirimkan skema penawaran resmi.', 1, DATE_SUB(NOW(), INTERVAL 1 DAY), NOW(), NOW());

-- --------------------------------------------------------
-- Table structure for `sent_emails`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `sent_emails`;
CREATE TABLE `sent_emails` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `to_email` varchar(150) NOT NULL,
  `to_name` varchar(150) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `body` longtext NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'sent',
  `error_message` text DEFAULT NULL,
  `inquiry_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sender_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sent_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`inquiry_id`) REFERENCES `inquiries` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS=1;
COMMIT;
