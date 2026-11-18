@extends('layouts.admin')

@section('title', isset($gtm) ? 'Edit Data GTM - PT Erickman' : 'Tambah Tabung GTM - PT Erickman')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.gtm.index') }}" class="text-xs font-semibold text-slate-500 hover:text-navy-900 flex items-center gap-1.5 mb-2 transition">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali ke Data GTM</span>
            </a>
            <h1 class="text-2xl font-bold text-navy-900">
                {{ isset($gtm) ? 'Edit Data Tabung GTM' : 'Tambah Tabung GTM Baru' }}
            </h1>
            <p class="text-xs text-slate-500">
                Isi formulir data tabung bertekanan tinggi Gas Transport Module (GTM).
            </p>
        </div>
    </div>

    <!-- Error Validation Alert -->
    @if($errors->any())
    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
        <div class="font-bold flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>Terdapat beberapa kesalahan input:</span>
        </div>
        <ul class="list-disc list-inside text-rose-700 pl-2">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Form Container -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <form action="{{ isset($gtm) ? route('admin.gtm.update', $gtm->id) : route('admin.gtm.store') }}" method="POST" class="space-y-6">
            @csrf
            @if(isset($gtm))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                <!-- Nomor Urut -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Nomor Urut
                    </label>
                    <input type="number" name="order_number" value="{{ old('order_number', $gtm->order_number ?? $nextOrder ?? 1) }}" min="1" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-600 focus:outline-none font-mono">
                    <span class="text-[11px] text-slate-400 mt-1 block">Urutan tampilan pada tabel dan laporan cetak.</span>
                </div>

                <!-- Kode Tabung (Wajib) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Kode Tabung <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="tube_code" value="{{ old('tube_code', $gtm->tube_code ?? '') }}" required placeholder="Contoh: GTM-CNG-01, TB-2024-001" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-600 focus:outline-none font-mono font-bold uppercase">
                    <span class="text-[11px] text-slate-400 mt-1 block">Nomor identifikasi unik tabung / skid.</span>
                </div>

                <!-- Merek Tabung (Wajib) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Merek Tabung <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="tube_brand" value="{{ old('tube_brand', $gtm->tube_brand ?? '') }}" required placeholder="Contoh: Worthington, NK, Everest, FTI" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-600 focus:outline-none">
                    <span class="text-[11px] text-slate-400 mt-1 block">Pabrikan manufaktur tabung CNG.</span>
                </div>

                <!-- Kapasitas (Wajib) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Kapasitas <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="capacity" value="{{ old('capacity', $gtm->capacity ?? '') }}" required placeholder="Contoh: 1.200 Nm3, 3.500 Liter, 250 Bar" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-600 focus:outline-none">
                    <span class="text-[11px] text-slate-400 mt-1 block">Volume gas atau kapasitas air (water capacity).</span>
                </div>

                <!-- Tahun Pembuatan (Wajib) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Tahun Pembuatan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="manufacture_year" value="{{ old('manufacture_year', $gtm->manufacture_year ?? date('Y')) }}" required placeholder="Contoh: 2022, 2023" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-600 focus:outline-none font-mono">
                    <span class="text-[11px] text-slate-400 mt-1 block">Tahun pembuatan fisik tabung bertekanan.</span>
                </div>

                <!-- Status Tabung -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Status Operasional <span class="text-rose-500">*</span>
                    </label>
                    <select name="status" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-600 focus:outline-none">
                        <option value="aktif" {{ old('status', $gtm->status ?? 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif / Beroperasi</option>
                        <option value="standby" {{ old('status', $gtm->status ?? '') == 'standby' ? 'selected' : '' }}>Standby / Cadangan</option>
                        <option value="rekualifikasi" {{ old('status', $gtm->status ?? '') == 'rekualifikasi' ? 'selected' : '' }}>Rekualifikasi / Uji Berkala</option>
                        <option value="perbaikan" {{ old('status', $gtm->status ?? '') == 'perbaikan' ? 'selected' : '' }}>Perbaikan / Perawatan</option>
                    </select>
                </div>

                <!-- Unit Armada Terpasang (Opsional) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Unit Armada Terpasang (Truk / Trailer)
                    </label>
                    <select name="fleet_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-600 focus:outline-none">
                        <option value="">-- Tidak Terpasang (Standby / Depo) --</option>
                        @foreach($fleets as $fleet)
                        <option value="{{ $fleet->id }}" {{ old('fleet_id', $gtm->fleet_id ?? '') == $fleet->id ? 'selected' : '' }}>
                            {{ $fleet->plate_number }} - {{ $fleet->vehicle_name }} ({{ $fleet->type }})
                        </option>
                        @endforeach
                    </select>
                    <span class="text-[11px] text-slate-400 mt-1 block">Opsional: pilih unit armada yang membawa tabung ini.</span>
                </div>

                <!-- Lokasi Tabung (Ketik Bebas) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Lokasi Tabung
                    </label>
                    <input type="text" name="location" value="{{ old('location', $gtm->location ?? '') }}" placeholder="Ketik lokasi tabung (contoh: Depo Cikarang, SPBG Cilegon, Trailer 02)" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-600 focus:outline-none">
                    <span class="text-[11px] text-slate-400 mt-1 block">Ketik nama depo, stasiun gas, pabrik, atau lokasi fisik tabung saat ini.</span>
                </div>

                <!-- COI / MDR -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        COI / MDR
                    </label>
                    <input type="text" name="coi_mdr" value="{{ old('coi_mdr', $gtm->coi_mdr ?? '') }}" placeholder="Ketik status / masa berlaku / No. Sertifikat COI & MDR" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-600 focus:outline-none">
                    <span class="text-[11px] text-slate-400 mt-1 block">Certificate of Inspection (COI) / Manufacturer Data Report (MDR).</span>
                </div>

            </div>

            <!-- Catatan Tambahan -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Catatan / Spesifikasi Teknis Tambahan
                </label>
                <textarea name="notes" rows="3" placeholder="Informasi tekanan kerja (Working Pressure), nomor seri pabrik, lokasi depo penyimpanan, dll." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-600 focus:outline-none">{{ old('notes', $gtm->notes ?? '') }}</textarea>
            </div>

            <!-- Tombol Simpan -->
            <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3">
                <a href="{{ route('admin.gtm.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold text-xs transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md shadow-brand-600/20 transition flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>{{ isset($gtm) ? 'Simpan Perubahan' : 'Simpan Data Tabung GTM' }}</span>
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
