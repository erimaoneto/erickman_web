@extends('layouts.admin')

@section('title', 'Pengaturan Penanda Tangan Laporan Keuangan')
@section('page_title', 'Pengaturan Penanda Tangan Cetak Laporan')

@section('content')
<div class="space-y-6 max-w-5xl">

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

    <!-- Header Actions & Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-navy-900">Penanda Tangan Laporan Keuangan</h2>
            <p class="text-xs text-slate-500">Sesuaikan nama, jabatan, dan label penanda tangan resmi yang muncul di lembar cetak laporan keuangan.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.finance.report') }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-navy-900 font-bold text-xs uppercase tracking-wider transition flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-eye text-brand-600"></i>
                <span>Lihat Hasil Cetak</span>
            </a>
            <a href="{{ route('admin.finance.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs uppercase tracking-wider transition flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- Main Configuration Form -->
    <form action="{{ route('admin.finance.signatures.update') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
            
            <!-- Kota Penerbitan -->
            <div class="max-w-md">
                <label for="report_city" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Kota Tempat Laporan Diterbitkan
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-location-dot"></i>
                    </span>
                    <input type="text" name="report_city" id="report_city" 
                           value="{{ old('report_city', $settings['report_city'] ?? 'Jakarta') }}" 
                           placeholder="Contoh: Jakarta"
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-600 focus:border-brand-600 font-medium">
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Dicantumkan pada penanggalan laporan (contoh: Jakarta, {{ date('d F Y') }}).</p>
            </div>

            <div class="border-t border-slate-100 pt-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- Pihak 1: Disiapkan Oleh (Kiri) -->
                <div class="p-5 sm:p-6 rounded-2xl bg-slate-50/70 border border-slate-200 space-y-4">
                    <div class="flex items-center gap-2.5 text-navy-900 font-bold text-sm pb-2 border-b border-slate-200">
                        <div class="w-7 h-7 rounded-lg bg-brand-100 text-brand-700 flex items-center justify-center text-xs">
                            1
                        </div>
                        <span>Pihak Pembuat Laporan (Kiri)</span>
                    </div>

                    <div>
                        <label for="report_signer_1_title" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Label Posisi / Kalimat Pengantar <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="report_signer_1_title" id="report_signer_1_title"
                               value="{{ old('report_signer_1_title', $settings['report_signer_1_title'] ?? 'Disiapkan Oleh,') }}"
                               placeholder="Contoh: Disiapkan Oleh,"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-600 focus:border-brand-600 font-medium" required>
                    </div>

                    <div>
                        <label for="report_signer_1_name" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Nama Lengkap Penanda Tangan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="report_signer_1_name" id="report_signer_1_name"
                               value="{{ old('report_signer_1_name', $settings['report_signer_1_name'] ?? 'Staff Keuangan & Logistik') }}"
                               placeholder="Contoh: Staff Keuangan & Logistik atau Nama Terang"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-600 focus:border-brand-600 font-medium" required>
                    </div>

                    <div>
                        <label for="report_signer_1_role" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Keterangan / Jabatan / Perusahaan
                        </label>
                        <input type="text" name="report_signer_1_role" id="report_signer_1_role"
                               value="{{ old('report_signer_1_role', $settings['report_signer_1_role'] ?? 'PT. Erickman Sarana Abadi') }}"
                               placeholder="Contoh: PT. Erickman Sarana Abadi atau Divisi Keuangan"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-600 focus:border-brand-600 font-medium">
                    </div>
                </div>

                <!-- Pihak 2: Disetujui Oleh (Kanan) -->
                <div class="p-5 sm:p-6 rounded-2xl bg-slate-50/70 border border-slate-200 space-y-4">
                    <div class="flex items-center gap-2.5 text-navy-900 font-bold text-sm pb-2 border-b border-slate-200">
                        <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs">
                            2
                        </div>
                        <span>Pihak Penyetuju / Pimpinan (Kanan)</span>
                    </div>

                    <div>
                        <label for="report_signer_2_title" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Label Posisi / Kalimat Pengantar <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="report_signer_2_title" id="report_signer_2_title"
                               value="{{ old('report_signer_2_title', $settings['report_signer_2_title'] ?? 'Disetujui Oleh,') }}"
                               placeholder="Contoh: Disetujui Oleh,"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-600 focus:border-brand-600 font-medium" required>
                    </div>

                    <div>
                        <label for="report_signer_2_name" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Nama Lengkap Penanda Tangan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="report_signer_2_name" id="report_signer_2_name"
                               value="{{ old('report_signer_2_name', $settings['report_signer_2_name'] ?? 'Direksi / Manajemen') }}"
                               placeholder="Contoh: Direksi / Manajemen atau Nama Direktur"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-600 focus:border-brand-600 font-medium" required>
                    </div>

                    <div>
                        <label for="report_signer_2_role" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Keterangan / Jabatan / Perusahaan
                        </label>
                        <input type="text" name="report_signer_2_role" id="report_signer_2_role"
                               value="{{ old('report_signer_2_role', $settings['report_signer_2_role'] ?? 'PT. Erickman Sarana Abadi') }}"
                               placeholder="Contoh: Direktur Utama atau PT. Erickman Sarana Abadi"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-600 focus:border-brand-600 font-medium">
                    </div>
                </div>

            </div>

            <!-- Live Preview Card -->
            <div class="border-t border-slate-100 pt-6">
                <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Pratinjau Tampilan di Lembar Cetak</span>
                <div class="p-6 bg-white rounded-xl border-2 border-dashed border-slate-200">
                    <div class="text-right text-xs text-slate-500 mb-8 font-medium">
                        {{ $settings['report_city'] ?? 'Jakarta' }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                    </div>
                    <div class="grid grid-cols-2 gap-8 text-xs text-center">
                        <div>
                            <span class="text-slate-400 block mb-12">{{ $settings['report_signer_1_title'] ?? 'Disiapkan Oleh,' }}</span>
                            <span class="font-bold text-slate-800 block underline">{{ $settings['report_signer_1_name'] ?? 'Staff Keuangan & Logistik' }}</span>
                            <span class="text-slate-500 text-[11px]">{{ $settings['report_signer_1_role'] ?? 'PT. Erickman Sarana Abadi' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-12">{{ $settings['report_signer_2_title'] ?? 'Disetujui Oleh,' }}</span>
                            <span class="font-bold text-slate-800 block underline">{{ $settings['report_signer_2_name'] ?? 'Direksi / Manajemen' }}</span>
                            <span class="text-slate-500 text-[11px]">{{ $settings['report_signer_2_role'] ?? 'PT. Erickman Sarana Abadi' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-brand-600/20 transition flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Pengaturan Penanda Tangan</span>
                </button>
            </div>

        </div>
    </form>

</div>
@endsection
