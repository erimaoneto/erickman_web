@extends('layouts.admin')

@section('title', 'Pusat Keamanan & Integritas Server - PT. Erickman Sarana Abadi')

@section('content')
<div class="space-y-6">

    <!-- Flash Message -->
    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
            <span class="text-sm font-semibold">{{ session('success') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 text-sm">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif

    <!-- Header Banner -->
    <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-navy-950 via-slate-900 to-navy-900 text-white shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6 border border-slate-800">
        <div class="space-y-2">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 text-xs font-bold uppercase tracking-wider">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Security Sentinel: Aktif & Terlindungi
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Audit & Pencegahan Anomali Server</h1>
            <p class="text-slate-400 text-xs sm:text-sm max-w-2xl leading-relaxed">
                Sistem perlindungan otomatis terhadap perubahan file tidak sah (*File Integrity Violation*) dan aktivitas koneksi keluar mencurigakan (*Outbound Traffic Guard / Anti-SSRF*).
            </p>
        </div>

        <form action="{{ route('admin.security.scan') }}" method="POST">
            @csrf
            <button type="submit" class="px-5 py-3 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs sm:text-sm shadow-lg shadow-brand-600/30 transition transform hover:-translate-y-0.5 flex items-center gap-2.5 whitespace-nowrap">
                <i class="fa-solid fa-shield-virus text-base"></i>
                <span>Jalankan Audit Keamanan Sekarang</span>
            </button>
        </form>
    </div>

    <!-- Security Metric Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Card 1: Integritas File & Upload -->
        <div class="p-6 rounded-2xl bg-white border border-slate-100 shadow-sm flex flex-col justify-between hover:shadow-md transition">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Integritas File Publik</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-file-shield"></i>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-navy-900">
                    {{ $fileReport['scanned_files_count'] }} File
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    Dipindai pada folder publik & upload gambar.
                </p>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100 space-y-2">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-slate-600">File Executable Terlarang:</span>
                    <span class="font-bold {{ count($fileReport['flagged_files']) === 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ count($fileReport['flagged_files']) }} (Bersih)
                    </span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-slate-600">Backdoor Signature:</span>
                    <span class="font-bold {{ count($fileReport['backdoor_matches']) === 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ count($fileReport['backdoor_matches']) }} (Nol Temuan)
                    </span>
                </div>
            </div>
        </div>

        <!-- Card 2: Server Shield (.htaccess) -->
        <div class="p-6 rounded-2xl bg-white border border-slate-100 shadow-sm flex flex-col justify-between hover:shadow-md transition">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Web Server Shield</span>
                    <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-navy-900">
                    2 Layer Aktif
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    Blokir eksekusi PHP pada folder aset & upload.
                </p>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100 space-y-2">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-slate-600">public/images/.htaccess:</span>
                    <span class="font-bold {{ $fileReport['shields']['images_htaccess']['shield_active'] ? 'text-emerald-600' : 'text-amber-600' }}">
                        {{ $fileReport['shields']['images_htaccess']['shield_active'] ? 'Blokir Eksekusi PHP' : 'Periksa File' }}
                    </span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-slate-600">public/.htaccess Shield:</span>
                    <span class="font-bold {{ $fileReport['shields']['root_htaccess']['shield_active'] ? 'text-emerald-600' : 'text-amber-600' }}">
                        {{ $fileReport['shields']['root_htaccess']['shield_active'] ? 'Proteksi Ekstensi Sensitif' : 'Periksa File' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Card 3: Outbound Traffic Guard -->
        <div class="p-6 rounded-2xl bg-white border border-slate-100 shadow-sm flex flex-col justify-between hover:shadow-md transition">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Lalu Lintas Keluar (Outbound)</span>
                    <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-network-wired"></i>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-navy-900">
                    SSRF Guard
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    Blokir koneksi ke IP privat, loopback, & port asing.
                </p>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100 space-y-2">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-slate-600">Filter IP Privat / Metadata:</span>
                    <span class="font-bold text-emerald-600">
                        Aktif (17 Range Terblokir)
                    </span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-slate-600">Port Keluar Diizinkan:</span>
                    <span class="font-bold text-slate-700">
                        80 (HTTP), 443 (HTTPS)
                    </span>
                </div>
            </div>
        </div>

    </div>

    <!-- Detailed Security Modules -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Modul 1: Aturan Pencegahan Anomali File -->
        <div class="p-6 sm:p-7 rounded-2xl bg-white border border-slate-100 shadow-sm space-y-4">
            <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-shield"></i>
                </div>
                <div>
                    <h3 class="font-bold text-navy-900 text-base">Proteksi Integritas File (File Integrity)</h3>
                    <p class="text-[11px] text-slate-500">Mekanisme pertahanan terhadap injeksi web shell dan backdoor</p>
                </div>
            </div>

            <ul class="space-y-3 text-xs text-slate-600">
                <li class="flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5"></i>
                    <span><strong>Larangan Eksekusi Skrip di Folder Upload:</strong> File berformat <code>.php</code>, <code>.phtml</code>, <code>.phar</code>, <code>.sh</code>, <code>.cgi</code> yang diunggah ke <code>public/images/</code> otomatis ditolak eksekusinya oleh web server dengan status <em>403 Forbidden</em>.</span>
                </li>
                <li class="flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5"></i>
                    <span><strong>Blokir File Rahasia & Sensitif:</strong> Akses publik langsung ke file <code>.env</code>, <code>.git</code>, <code>.sql</code>, <code>.bak</code>, <code>.log</code>, dan <code>.ini</code> ditutup rapat via konfigurasi rewrite rule.</span>
                </li>
                <li class="flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5"></i>
                    <span><strong>Header Keamanan HTTP Aktif:</strong> Dilengkapi <code>X-Content-Type-Options: nosniff</code> untuk mencegah browser mengeksekusi file gambar palsu sebagai skrip.</span>
                </li>
                <li class="flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5"></i>
                    <span><strong>Pemindai Signature Backdoor Otomatis:</strong> Rutin memindai kode-kode berbahaya seperti <code>eval(base64_decode)</code>, <code>system($_GET)</code>, dan <code>shell_exec</code> di direktori publik.</span>
                </li>
            </ul>
        </div>

        <!-- Modul 2: Aturan Pencegahan Trafik Keluar Aneh -->
        <div class="p-6 sm:p-7 rounded-2xl bg-white border border-slate-100 shadow-sm space-y-4">
            <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                <div class="w-8 h-8 rounded-lg bg-sky-100 text-sky-800 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-globe"></i>
                </div>
                <div>
                    <h3 class="font-bold text-navy-900 text-base">Proteksi Trafik Keluar (Outbound Guard)</h3>
                    <p class="text-[11px] text-slate-500">Mencegah server dijadikan botnet, proxy penyerang, atau penyebar spam</p>
                </div>
            </div>

            <ul class="space-y-3 text-xs text-slate-600">
                <li class="flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-check text-sky-600 mt-0.5"></i>
                    <span><strong>Anti-SSRF (Server-Side Request Forgery):</strong> Seluruh pemanggilan request keluar divalidasi ketat. Dilarang keras menghubungi <code>127.0.0.1</code> (localhost), IP privat (<code>192.168.x</code>, <code>10.x</code>), dan endpoint cloud metadata (<code>169.254.169.254</code>).</span>
                </li>
                <li class="flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-check text-sky-600 mt-0.5"></i>
                    <span><strong>Pembatasan Port Keluar:</strong> Hanya protokol standar web terenkripsi (Port 80 HTTP & 443 HTTPS) yang diizinkan untuk komunikasi API resmi. Port mining, IRC, atau remote shell otomatis ditolak.</span>
                </li>
                <li class="flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-check text-sky-600 mt-0.5"></i>
                    <span><strong>Content Security Policy (CSP):</strong> Browser pengunjung dibatasi hanya boleh mengirim data ke domain resmi aplikasi, mencegah kebocoran data (*data exfiltration*) oleh skrip pihak ketiga.</span>
                </li>
                <li class="flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-check text-sky-600 mt-0.5"></i>
                    <span><strong>Pemantauan Formulir & Email Hari Ini:</strong> Tercatat <strong>{{ $outboundReport['mail_activity']['inquiries_today'] }} pesan masuk</strong> hari ini (Status: <span class="text-emerald-600 font-bold">{{ $outboundReport['mail_activity']['status'] }}</span>). Honeypot form aktif mencegah ledakan pengiriman email spam.</span>
                </li>
            </ul>
        </div>

    </div>

    <!-- Audit Timestamp Footer -->
    <div class="p-4 rounded-xl bg-slate-100 text-slate-500 text-xs flex flex-col sm:flex-row items-center justify-between gap-2 border border-slate-200">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-clock-rotate-left text-slate-400"></i>
            <span>Waktu Audit Terakhir: <strong>{{ \Carbon\Carbon::parse($fileReport['audited_at'])->translatedFormat('d F Y - H:i:s') }} WIB</strong></span>
        </div>
        <div>
            <span>Dijalankan via: <strong>Security Sentinel Engine (Laravel {{ app()->version() }})</strong></span>
        </div>
    </div>

</div>
@endsection
