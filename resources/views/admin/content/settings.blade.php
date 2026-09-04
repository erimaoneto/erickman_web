@extends('layouts.admin')

@section('title', 'Pengaturan Website & Legalitas')
@section('page_title', 'CMS: Profil Perusahaan, NIB & Kontak')

@section('content')

    <form action="{{ route('admin.content.settings.update') }}" method="POST" class="space-y-8">
        @csrf

        <!-- Legalitas & Identitas Resmi -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-brand-100 text-brand-800 text-xs font-bold uppercase tracking-wider mb-2">
                    Legalitas NIB & Profil
                </div>
                <h3 class="text-lg font-bold text-navy-900">Informasi Perusahaan & Perizinan Berusaha</h3>
                <p class="text-xs text-slate-500">Data legalitas resmi yang tampil pada header, profil, dan footer website.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Perusahaan</label>
                    <input type="text" name="company_name" value="{{ $settings['company']->where('key', 'company_name')->first()->value ?? 'PT Erickman' }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-brand-600 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor Induk Berusaha (NIB Resmi)</label>
                    <input type="text" name="company_nib" value="{{ $settings['company']->where('key', 'company_nib')->first()->value ?? '2211210015706' }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-brand-600 text-sm font-mono font-bold">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tagline / Slogan Perusahaan</label>
                    <input type="text" name="company_tagline" value="{{ $settings['company']->where('key', 'company_tagline')->first()->value ?? '' }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-brand-600 text-sm">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi Singkat (Tampil di Beranda & Footer)</label>
                    <textarea name="company_description" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-brand-600 text-sm">{{ $settings['company']->where('key', 'company_description')->first()->value ?? '' }}</textarea>
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
                    <textarea name="company_address" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-brand-600 text-sm">{{ $settings['contact']->where('key', 'company_address')->first()->value ?? '' }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor Telepon Kantor</label>
                    <input type="text" name="company_phone" value="{{ $settings['contact']->where('key', 'company_phone')->first()->value ?? '' }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-brand-600 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Email Resmi</label>
                    <input type="email" name="company_email" value="{{ $settings['contact']->where('key', 'company_email')->first()->value ?? '' }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-brand-600 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor WhatsApp Hotline</label>
                    <input type="text" name="company_whatsapp" value="{{ $settings['contact']->where('key', 'company_whatsapp')->first()->value ?? '' }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-brand-600 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jam Operasional Layanan</label>
                    <input type="text" name="operational_hours" value="{{ $settings['contact']->where('key', 'operational_hours')->first()->value ?? '' }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-brand-600 text-sm">
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
                    <textarea name="about_story" rows="4" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-brand-600 text-sm">{{ $settings['about']->where('key', 'about_story')->first()->value ?? '' }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Visi Perusahaan</label>
                        <textarea name="company_vision" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-brand-600 text-sm">{{ $settings['about']->where('key', 'company_vision')->first()->value ?? '' }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Misi Perusahaan</label>
                        <textarea name="company_mission" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-brand-600 text-sm">{{ $settings['about']->where('key', 'company_mission')->first()->value ?? '' }}</textarea>
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
