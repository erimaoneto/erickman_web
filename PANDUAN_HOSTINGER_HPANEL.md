# Panduan Lengkap Deploy Website & Admin Panel Erickman di Hostinger hPanel

Panduan ini disusun khusus untuk domain **`erickman.co.id`** di hosting **Hostinger (hPanel)** dengan database **MySQL**.

---

## 1. Setup Database MySQL di Hostinger hPanel

1. Masuk ke **hPanel Hostinger** &rarr; Pilih akun hosting Anda.
2. Di menu sebelah kiri, buka **Databases** &rarr; **MySQL Databases**.
3. Buat database baru:
   - **MySQL Database Name**: contoh `u123456789_erickman`
   - **MySQL Username**: contoh `u123456789_admin`
   - **Password**: Buat kata sandi yang kuat (simpan untuk langkah konfigurasi `.env`).
   - Klik **Create**.
4. Di bagian daftar database, klik tombol **Enter phpMyAdmin** di samping database yang baru dibuat.
5. Klik tab **Import** di phpMyAdmin, pilih file:
   ```
   database/erickman_mysql_ready.sql
   ```
   Lalu klik **Go** / **Kirim**. Seluruh tabel dan data awal (profil, foto armada, NIB, admin) langsung selesai di-import!

---

## 2. Unggah (Upload) File Website ke Hostinger

Anda dapat memilih salah satu cara berikut:

### Opsi A: Menggunakan Git / SSH hPanel (Rekomendasi Tercepat)
1. Buka menu **Advanced** &rarr; **SSH Access** di hPanel, aktifkan SSH.
2. Buka **Terminal** di hPanel atau konek via terminal Mac Anda:
   ```bash
   ssh -p PORT_ANDA uXXXXX@IP_HOSTINGER
   ```
3. Arahkan ke folder domain Anda, clone atau upload source code:
   ```bash
   cd ~/domains/erickman.co.id/public_html
   # Jalankan composer jika perlu menginstal dependensi
   composer install --no-dev --optimize-autoloader
   php artisan key:generate
   php artisan storage:link
   ```

### Opsi B: Menggunakan File Manager hPanel (Format ZIP)
1. Zip seluruh folder project `erickman` ini (kecuali folder `vendor` dan `node_modules` jika ingin ukuran kecil, atau sertakan seluruhnya).
2. Buka **Files** &rarr; **File Manager** di hPanel.
3. Masuk ke folder root domain `public_html`.
4. Upload file ZIP lalu klik **Extract**.

---

## 3. Pengaturan Document Root & `.htaccess`

Hostinger LiteSpeed Web Server memerlukan titik masuk aplikasi web di folder `public`.
Anda memiliki 2 cara mudah:

1. **Cara Otomatis (.htaccess bawaan)**:
   Proyek ini sudah dilengkapi file [`.htaccess`](file:///.htaccess) di root folder yang otomatis mengarahkan seluruh lalu lintas ke folder `public/`.
2. **Cara Pengaturan Root Folder di hPanel (Paling Bersih)**:
   - Di hPanel, buka **Websites** &rarr; Klik titik tiga pada `erickman.co.id` &rarr; Pilih **Website Settings** / **Change Document Root**.
   - Ubah Document Root dari `public_html` menjadi `public_html/public`.

---

## 4. Konfigurasi File `.env` di Server Hostinger

1. Salin isi file [`.env.hostinger.example`](file:///.env.hostinger.example) menjadi file `.env` di server.
2. Sesuaikan informasi kredensial yang dibuat di Langkah 1:
   ```env
   APP_NAME="PT Erickman"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://erickman.co.id

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=u123456789_erickman
   DB_USERNAME=u123456789_admin
   DB_PASSWORD=KataSandiDatabaseAnda
   ```

---

## 5. Konfigurasi Email Resmi Hostinger (Webmail SMTP)

Website ini memiliki fitur **Kirim Email Resmi** dan **Notifikasi Permintaan Penawaran**.

1. Di hPanel, buka menu **Emails** &rarr; **Email Accounts**.
2. Buat akun email domain, misalnya: `admin@erickman.co.id` atau `info@erickman.co.id`.
3. Masukkan pengaturannya ke dalam file `.env`:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.hostinger.com
   MAIL_PORT=465
   MAIL_USERNAME=admin@erickman.co.id
   MAIL_PASSWORD=PasswordEmailHostingerAnda
   MAIL_ENCRYPTION=ssl
   MAIL_FROM_ADDRESS="admin@erickman.co.id"
   MAIL_FROM_NAME="PT Erickman"
   ```

---

## 6. Akses Admin Panel & Kredensial Default

Setelah website aktif:
- **URL Admin Panel**: `https://erickman.co.id/login` atau `https://erickman.co.id/admin`
- **Email Default**: `admin@erickman.co.id`
- **Password Default**: `admin12345`

> **Saran Keamanan**: Setelah berhasil masuk untuk pertama kalinya, Anda dapat mengubah kata sandi dan profil admin sesuai preferensi Anda.

---

## 7. Fitur yang Siap Digunakan di Admin Panel

1. **CMS Konten Website**: Mengubah tagline, nomor telepon, alamat kantor 18 Office Park TB Simatupang, NIB resmi, dan deskripsi visi-misi.
2. **Kelola Banner Slider Foto**: Menambah foto armada baru, mengatur urutan slide hero beranda.
3. **Kelola Layanan (KBLI)**: Menambah atau mengedit layanan distribusi gas alam dan logistik khusus.
4. **Dashboard Manajemen Armada**:
   - Monitoring ketersediaan unit truk (*Tersedia*, *Dalam Perjalanan*, *Perawatan*).
   - Pengingat otomatis masa berlaku **Uji KIR** dan **Pajak STNK** (&lt; 30 hari & expired).
   - Pencatatan riwayat servis berkala & biaya pemeliharaan.
5. **Dashboard Keuangan (Cashflow)**:
   - Pencatatan transaksi pemasukan dan pengeluaran operasional (BBM, uang jalan driver, tol, sewa unit).
   - Grafik arus kas 6 bulan terakhir.
   - Fitur cetak laporan keuangan bulanan berformat resmi.
6. **Modul Email**:
   - Kotak masuk (*Inbox*) pesan penawaran dari calon klien.
   - Form kirim email penawaran resmi (*Compose*) langsung menggunakan SMTP domain Hostinger.
   - Log email terkirim (*Outbox*).
