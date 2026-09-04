@extends('layouts.admin')

@section('title', isset($fleet) ? 'Edit Armada ' . $fleet->plate_number : 'Tambah Unit Truk')
@section('page_title', isset($fleet) ? 'Edit Data Armada: ' . $fleet->plate_number : 'Pendaftaran Unit Truk Baru')

@section('content')

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h2 class="text-lg font-bold text-navy-900">{{ isset($fleet) ? 'Perbarui Data Truk' : 'Formulir Armada Baru' }}</h2>
                    <p class="text-xs text-slate-500">Lengkapi spesifikasi unit, supir, serta masa berlaku uji berkala KIR.</p>
                </div>
                <a href="{{ route('admin.fleets.index') }}" class="text-xs font-semibold text-slate-500 hover:text-navy-900 flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            </div>

            <form action="{{ isset($fleet) ? route('admin.fleets.update', $fleet->id) : route('admin.fleets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @if(isset($fleet))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Nomor Polisi (Plat No.) *</label>
                        <input type="text" name="plate_number" value="{{ old('plate_number', $fleet->plate_number ?? '') }}" required placeholder="Contoh: B 9108 UEM" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm font-mono uppercase font-bold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Nama / Identitas Unit *</label>
                        <input type="text" name="vehicle_name" value="{{ old('vehicle_name', $fleet->vehicle_name ?? '') }}" required placeholder="Contoh: Prime Mover CNG Cradle 01" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Tipe Armada *</label>
                        <select name="type" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm">
                            <option value="CNG Tube Trailer" {{ old('type', $fleet->type ?? '') == 'CNG Tube Trailer' ? 'selected' : '' }}>CNG Tube Trailer</option>
                            <option value="Box Truck" {{ old('type', $fleet->type ?? '') == 'Box Truck' ? 'selected' : '' }}>Box Truck (Truk Boks)</option>
                            <option value="Heavy Box Truck" {{ old('type', $fleet->type ?? '') == 'Heavy Box Truck' ? 'selected' : '' }}>Heavy Box Truck (Fuso/Tronton)</option>
                            <option value="Flatbed Trailer" {{ old('type', $fleet->type ?? '') == 'Flatbed Trailer' ? 'selected' : '' }}>Flatbed Trailer</option>
                            <option value="Tangki Khusus" {{ old('type', $fleet->type ?? '') == 'Tangki Khusus' ? 'selected' : '' }}>Tangki Khusus / B3</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Merk / Seri Kendaraan</label>
                        <input type="text" name="brand" value="{{ old('brand', $fleet->brand ?? '') }}" placeholder="Contoh: UD Quester / Toyota Dyna" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Tahun Pembuatan</label>
                        <input type="text" name="year" value="{{ old('year', $fleet->year ?? '') }}" placeholder="Contoh: 2022" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Kapasitas Muatan</label>
                        <input type="text" name="capacity" value="{{ old('capacity', $fleet->capacity ?? '') }}" placeholder="Contoh: 2.500 m³ / 8.5 Ton" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Status Operasional *</label>
                        <select name="status" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm font-semibold">
                            <option value="Tersedia" {{ old('status', $fleet->status ?? '') == 'Tersedia' ? 'selected' : '' }}>Tersedia (Standby)</option>
                            <option value="Dalam Perjalanan" {{ old('status', $fleet->status ?? '') == 'Dalam Perjalanan' ? 'selected' : '' }}>Dalam Perjalanan (Active Trip)</option>
                            <option value="Perawatan" {{ old('status', $fleet->status ?? '') == 'Perawatan' ? 'selected' : '' }}>Perawatan (Maintenance)</option>
                            <option value="Non-Aktif" {{ old('status', $fleet->status ?? '') == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Nama Supir (Driver)</label>
                        <input type="text" name="driver_name" value="{{ old('driver_name', $fleet->driver_name ?? '') }}" placeholder="Contoh: Bambang Supriyanto" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm">
                    </div>
                </div>

                <!-- Legalitas Expiry Dates -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-200">
                    <div>
                        <label class="block text-xs font-bold text-navy-900 uppercase mb-1.5">
                            <i class="fa-solid fa-calendar-check text-brand-600 mr-1"></i> Tanggal Jatuh Tempo Uji KIR
                        </label>
                        <input type="date" name="kir_expiry" value="{{ old('kir_expiry', isset($fleet) && $fleet->kir_expiry ? $fleet->kir_expiry->format('Y-m-d') : '') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm bg-white">
                        <span class="text-[10px] text-slate-500 mt-1 block">Sistem akan memberi peringatan jika &lt; 30 hari.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-navy-900 uppercase mb-1.5">
                            <i class="fa-solid fa-calendar-days text-brand-600 mr-1"></i> Tanggal Jatuh Tempo Pajak STNK
                        </label>
                        <input type="date" name="stnk_expiry" value="{{ old('stnk_expiry', isset($fleet) && $fleet->stnk_expiry ? $fleet->stnk_expiry->format('Y-m-d') : '') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm bg-white">
                        <span class="text-[10px] text-slate-500 mt-1 block">Untuk pembaruan STNK tahunan / 5 tahunan.</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Foto Kendaraan</label>
                    @if(isset($fleet) && $fleet->image_path)
                    <div class="mb-3 flex items-center gap-3">
                        <img src="{{ asset($fleet->image_path) }}" class="w-24 h-16 object-cover rounded-xl border border-slate-200">
                        <span class="text-xs text-slate-500">Foto saat ini.</span>
                    </div>
                    @endif
                    <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Catatan Operasional / Rute Rutin</label>
                    <textarea name="notes" rows="3" placeholder="Informasi rute, perlengkapan tanggap darurat, atau riwayat khusus..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm">{{ old('notes', $fleet->notes ?? '') }}</textarea>
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
                    <a href="{{ route('admin.fleets.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 text-xs font-semibold hover:bg-slate-50">Batal</a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-600/20">
                        {{ isset($fleet) ? 'Simpan Perubahan' : 'Daftarkan Armada' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
