@extends('layouts.admin')

@section('title', 'Pengaturan Profil, Gambar & Modul Section')
@section('page_title', 'CMS: Profil Perusahaan, Gambar & Kontrol Section')

@section('content')

    @php
        $getVal = function($key, $default = '') use ($settings) {
            return $settings[$key] ?? $default;
        };
    @endphp

    <form action="{{ route('admin.content.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <!-- Modul Toggles & Saklar Tampilan Section -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-amber-100 text-amber-800 text-xs font-bold uppercase tracking-wider mb-2">
                    <i class="fa-solid fa-toggle-on"></i> Kontrol Modul & Formulir
                </div>
                <h3 class="text-lg font-bold text-navy-900">Saklar Tampilan Modul Landing Page</h3>
                <p class="text-xs text-slate-500">Aktifkan atau sembunyikan modul tertentu di halaman depan sesuai kebutuhan operasional.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- RFQ Form Toggle -->
                <div class="p-5 rounded-2xl border-2 {{ $getVal('show_rfq_form') == '1' ? 'border-emerald-500 bg-emerald-50/50' : 'border-slate-200 bg-slate-50/70' }} transition">
                    <div class="flex items-center justify-between">
                        <div class="space-y-1">
                            <span class="font-extrabold text-sm text-navy-900 block">Formulir RFQ (Penawaran)</span>
                            <span class="text-xs text-slate-500 block">Formulir input pesan & penawaran di seksi Kontak.</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer ml-3">
                            <input type="checkbox" name="show_rfq_form" value="1" {{ $getVal('show_rfq_form') == '1' ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>
                </div>

                <!-- Stats Toggle -->
                <div class="p-5 rounded-2xl border-2 {{ $getVal('show_stats_section', '1') == '1' ? 'border-emerald-500 bg-emerald-50/50' : 'border-slate-200 bg-slate-50/70' }} transition">
                    <div class="flex items-center justify-between">
                        <div class="space-y-1">
                            <span class="font-extrabold text-sm text-navy-900 block">Banner Counter Statistik</span>
                            <span class="text-xs text-slate-500 block">Angka rekor operasional di bawah slider.</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer ml-3">
                            <input type="checkbox" name="show_stats_section" value="1" {{ $getVal('show_stats_section', '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>
                </div>

                <!-- Armada Toggle -->
                <div class="p-5 rounded-2xl border-2 {{ $getVal('show_armada_section', '1') == '1' ? 'border-emerald-500 bg-emerald-50/50' : 'border-slate-200 bg-slate-50/70' }} transition">
                    <div class="flex items-center justify-between">
                        <div class="space-y-1">
                            <span class="font-extrabold text-sm text-navy-900 block">Seksi Armada Kami</span>
                            <span class="text-xs text-slate-500 block">Katalog armada truk dan spesifikasi.</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer ml-3">
                            <input type="checkbox" name="show_armada_section" value="1" {{ $getVal('show_armada_section', '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>
                </div>

                <!-- HSE Toggle -->
                <div class="p-5 rounded-2xl border-2 {{ $getVal('show_hse_section', '1') == '1' ? 'border-emerald-500 bg-emerald-50/50' : 'border-slate-200 bg-slate-50/70' }} transition">
                    <div class="flex items-center justify-between">
                        <div class="space-y-1">
                            <span class="font-extrabold text-sm text-navy-900 block">Seksi Standar HSE / K3</span>
                            <span class="text-xs text-slate-500 block">Komitmen keselamatan dan kepatuhan.</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer ml-3">
                            <input type="checkbox" name="show_hse_section" value="1" {{ $getVal('show_hse_section', '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>
                </div>

                <!-- Rekanan Toggle -->
                <div class="p-5 rounded-2xl border-2 {{ $getVal('show_rekanan_section', '1') == '1' ? 'border-emerald-500 bg-emerald-50/50' : 'border-slate-200 bg-slate-50/70' }} transition">
                    <div class="flex items-center justify-between">
                        <div class="space-y-1">
                            <span class="font-extrabold text-sm text-navy-900 block">Seksi Rekanan Kami</span>
                            <span class="text-xs text-slate-500 block">Mitra kerja strategis dan strip logo.</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer ml-3">
                            <input type="checkbox" name="show_rekanan_section" value="1" {{ $getVal('show_rekanan_section', '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Foto Seksi Tentang Kami & Banner Rekanan -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-brand-100 text-brand-800 text-xs font-bold uppercase tracking-wider mb-2">
                    <i class="fa-solid fa-images"></i> Media & Foto Seksi
                </div>
                <h3 class="text-lg font-bold text-navy-900">Foto Seksi Tentang Kami & Banner Rekanan</h3>
                <p class="text-xs text-slate-500">Ganti foto kolase pada profil dan gambar banner logo rekanan.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Foto Utama Tentang Kami -->
                <div class="space-y-3 p-4 rounded-xl border border-slate-200 bg-slate-50">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Foto Utama Tentang Kami</label>
                    <div class="h-40 rounded-xl overflow-hidden bg-slate-200 border">
                        <img src="{{ asset($getVal('about_image_main', '/images/truck-cng-green.jpg')) }}" alt="Foto Utama" class="w-full h-full object-cover">
                    </div>
                    <input type="file" name="about_image_main_file" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                </div>

                <!-- Foto Mengambang Tentang Kami -->
                <div class="space-y-3 p-4 rounded-xl border {{ $getVal('show_about_secondary_image', '1') == '1' ? 'border-emerald-300 bg-emerald-50/20' : 'border-slate-200 bg-slate-50' }} transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Foto Mengambang (Kecil)</label>
                            <span class="text-[10px] text-slate-500">Tampilkan di atas foto utama</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer ml-2">
                            <input type="checkbox" name="show_about_secondary_image" value="1" {{ $getVal('show_about_secondary_image', '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-600"></div>
                            <span class="ml-2 text-xs font-bold text-slate-500 peer-checked:text-emerald-700">
                                {{ $getVal('show_about_secondary_image', '1') == '1' ? 'Aktif' : 'Inaktif' }}
                            </span>
                        </label>
                    </div>
                    <div class="h-40 rounded-xl overflow-hidden bg-slate-200 border relative">
                        <img src="{{ asset($getVal('about_image_secondary', '/images/truck-box-red.jpg')) }}" alt="Foto Mengambang" class="w-full h-full object-cover">
                        @if($getVal('show_about_secondary_image', '1') != '1')
                        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[1px] flex items-center justify-center p-3 text-center">
                            <span class="px-2.5 py-1 rounded-md bg-slate-800 text-slate-200 text-xs font-semibold border border-slate-700 flex items-center gap-1.5 shadow">
                                <i class="fa-solid fa-eye-slash text-amber-400"></i> Status: Inaktif (Tersembunyi)
                            </span>
                        </div>
                        @endif
                    </div>
                    <input type="file" name="about_image_secondary_file" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                </div>

                <!-- Banner Strip Logo Rekanan -->
                <div class="space-y-3 p-4 rounded-xl border border-slate-200 bg-slate-50">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Banner Strip Logo Rekanan</label>
                    <div class="h-40 rounded-xl overflow-hidden bg-white border flex items-center justify-center p-2">
                        <img src="{{ asset($getVal('rekanan_banner_image', '/images/rekanan-kami-logos.png')) }}" alt="Banner Rekanan" class="max-h-full max-w-full object-contain">
                    </div>
                    <input type="file" name="rekanan_banner_image_file" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                </div>
            </div>
        </div>

        <!-- Teks Judul & Deskripsi Tiap Modul Section -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-brand-100 text-brand-800 text-xs font-bold uppercase tracking-wider mb-2">
                    <i class="fa-solid fa-heading"></i> Judul & Deskripsi Modul
                </div>
                <h3 class="text-lg font-bold text-navy-900">Judul, Badge & Deskripsi Tiap Section</h3>
                <p class="text-xs text-slate-500">Sesuaikan narasi pembuka untuk setiap bagian di landing page.</p>
            </div>

            <div class="space-y-6">
                <!-- Section Tentang Kami -->
                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 space-y-4">
                    <h4 class="font-bold text-sm text-navy-900 flex items-center gap-2">
                        <i class="fa-solid fa-building text-brand-600"></i> Section: Tentang Kami
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Badge Atas</label>
                            <input type="text" name="about_badge_text" value="{{ $getVal('about_badge_text', 'Legalitas & Integritas Terjamin') }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Judul Utama</label>
                            <input type="text" name="about_heading_text" value="{{ $getVal('about_heading_text', 'Pendistribusian Gas LPG, CNG, & Transportasi Migas') }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                        </div>
                    </div>
                </div>

                <!-- Section Armada -->
                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 space-y-4">
                    <h4 class="font-bold text-sm text-navy-900 flex items-center gap-2">
                        <i class="fa-solid fa-truck-moving text-brand-600"></i> Section: Armada Kami
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Badge Atas</label>
                            <input type="text" name="section_armada_badge" value="{{ $getVal('section_armada_badge', 'Armada Andal & Tangguh') }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Judul Utama</label>
                            <input type="text" name="section_armada_title" value="{{ $getVal('section_armada_title', 'Spesifikasi Kendaraan Operasional') }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                        </div>
                        <div class="sm:col-span-3">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Deskripsi Singkat</label>
                            <textarea name="section_armada_desc" rows="2" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">{{ $getVal('section_armada_desc') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Section HSE -->
                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 space-y-4">
                    <h4 class="font-bold text-sm text-navy-900 flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved text-brand-600"></i> Section: Standar Keselamatan (HSE)
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Badge Atas</label>
                            <input type="text" name="section_hse_badge" value="{{ $getVal('section_hse_badge', 'Safety First (HSE)') }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Judul Utama</label>
                            <input type="text" name="section_hse_title" value="{{ $getVal('section_hse_title', 'Komitmen Keselamatan & Keamanan Tertinggi') }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                        </div>
                        <div class="sm:col-span-3">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Deskripsi Singkat</label>
                            <textarea name="section_hse_desc" rows="2" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">{{ $getVal('section_hse_desc') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Section Rekanan -->
                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 space-y-4">
                    <h4 class="font-bold text-sm text-navy-900 flex items-center gap-2">
                        <i class="fa-solid fa-handshake-simple text-brand-600"></i> Section: Rekanan Kami
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Badge Atas</label>
                            <input type="text" name="section_rekanan_badge" value="{{ $getVal('section_rekanan_badge', 'Rekanan & Kemitraan Strategis') }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Judul Utama</label>
                            <input type="text" name="section_rekanan_title" value="{{ $getVal('section_rekanan_title', 'Rekanan Kami') }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                        </div>
                        <div class="sm:col-span-3">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Deskripsi Singkat</label>
                            <textarea name="section_rekanan_desc" rows="2" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">{{ $getVal('section_rekanan_desc') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Section Kontak -->
                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 space-y-4">
                    <h4 class="font-bold text-sm text-navy-900 flex items-center gap-2">
                        <i class="fa-solid fa-headset text-brand-600"></i> Section: Kontak Resmi
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Badge Atas</label>
                            <input type="text" name="section_kontak_badge" value="{{ $getVal('section_kontak_badge', 'Hubungi Kami') }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Judul Utama</label>
                            <input type="text" name="section_kontak_title" value="{{ $getVal('section_kontak_title', 'Kontak Resmi & Kantor Operasional') }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                        </div>
                        <div class="sm:col-span-3">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Deskripsi Singkat</label>
                            <textarea name="section_kontak_desc" rows="2" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">{{ $getVal('section_kontak_desc') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Legalitas & Identitas Resmi -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-brand-100 text-brand-800 text-xs font-bold uppercase tracking-wider mb-2">
                    Legalitas NIB & Identitas
                </div>
                <h3 class="text-lg font-bold text-navy-900">Informasi Perusahaan & Perizinan Berusaha</h3>
                <p class="text-xs text-slate-500">Data legalitas resmi yang tampil pada header, profil, dan footer website.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Perusahaan</label>
                    <input type="text" name="company_name" value="{{ $getVal('company_name', 'PT Erickman') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-brand-600 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor Induk Berusaha (NIB Resmi)</label>
                    <input type="text" name="company_nib" value="{{ $getVal('company_nib', '2211210015706') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-brand-600 text-sm font-mono font-bold">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tagline / Slogan Perusahaan</label>
                    <input type="text" name="company_tagline" value="{{ $getVal('company_tagline') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-brand-600 text-sm">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi Singkat (Tampil di Beranda & Footer)</label>
                    <textarea name="company_description" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-brand-600 text-sm">{{ $getVal('company_description') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Alamat Kantor & Kontak Resmi -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h3 class="text-lg font-bold text-navy-900">Alamat Kantor Pusat & Saluran Kontak</h3>
                <p class="text-xs text-slate-500">Sesuai lampiran dokumen NIB 18 Office Park TB Simatupang.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Lengkap Kantor Pusat</label>
                    <textarea name="company_address" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-brand-600 text-sm">{{ $getVal('company_address') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor Telepon Kantor</label>
                    <input type="text" name="company_phone" value="{{ $getVal('company_phone') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-brand-600 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Email Resmi</label>
                    <input type="email" name="company_email" value="{{ $getVal('company_email') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-brand-600 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor WhatsApp Hotline</label>
                    <input type="text" name="company_whatsapp" value="{{ $getVal('company_whatsapp') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-brand-600 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jam Operasional Layanan</label>
                    <input type="text" name="operational_hours" value="{{ $getVal('operational_hours') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-brand-600 text-sm">
                </div>
            </div>
        </div>

        <!-- Visi, Misi & Kisah Perusahaan -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h3 class="text-lg font-bold text-navy-900">Tentang Kami, Visi & Misi</h3>
                <p class="text-xs text-slate-500">Teks yang ditampilkan pada seksi Tentang Kami di halaman publik.</p>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Cerita / Profil Tentang Kami</label>
                    <textarea name="about_story" rows="4" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-brand-600 text-sm">{{ $getVal('about_story') }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Visi Perusahaan</label>
                        <textarea name="company_vision" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-brand-600 text-sm">{{ $getVal('company_vision') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Misi Perusahaan</label>
                        <textarea name="company_mission" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-brand-600 text-sm">{{ $getVal('company_mission') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Simpan -->
        <div class="flex justify-end pt-2">
            <button type="submit" class="px-8 py-3.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm shadow-md shadow-brand-600/30 transition transform hover:-translate-y-0.5 flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Simpan Seluruh Perubahan Konten</span>
            </button>
        </div>
    </form>

@endsection
