@extends('layouts.admin')

@section('title', isset($fleet) ? 'Edit Armada ' . $fleet->plate_number : 'Tambah Unit Truk')
@section('page_title', isset($fleet) ? 'Edit Data Armada: ' . $fleet->plate_number : 'Pendaftaran Unit Truk Baru')

@section('content')

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h2 class="text-lg font-bold text-navy-900">{{ isset($fleet) ? 'Perbarui Data Truk' : 'Formulir Armada Baru' }}</h2>
                    <p class="text-xs text-slate-500">Lengkapi nomor urut, spesifikasi unit, supir, serta berkas legalitas STNK, BPKB, dan KIR.</p>
                </div>
                <a href="{{ route('admin.fleets.index') }}" class="text-xs font-semibold text-slate-500 hover:text-navy-900 flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            </div>

            @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1 shadow-sm">
                @foreach($errors->all() as $error)
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-xmark text-rose-600"></i>
                    <span>{{ $error }}</span>
                </div>
                @endforeach
            </div>
            @endif

            <form action="{{ isset($fleet) ? route('admin.fleets.update', $fleet->id) : route('admin.fleets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @if(isset($fleet))
                    @method('PUT')
                @endif

                <!-- Baris 1: Nomor Urut, Plat Nomor, Nama Kendaraan -->
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                    <div class="sm:col-span-3">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                            <i class="fa-solid fa-arrow-down-1-9 text-brand-600 mr-1"></i> No. Urut *
                        </label>
                        <input type="number" name="order_number" value="{{ old('order_number', $fleet->order_number ?? $nextOrder ?? 1) }}" min="1" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm font-bold text-navy-900">
                        <span class="text-[10px] text-slate-400 mt-1 block">Urutan tampilan di tabel & cetak.</span>
                    </div>

                    <div class="sm:col-span-4">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Nomor Polisi (Plat No.) *</label>
                        <input type="text" name="plate_number" value="{{ old('plate_number', $fleet->plate_number ?? '') }}" required placeholder="Contoh: B 9108 UEM" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm font-mono uppercase font-bold text-navy-900">
                    </div>

                    <div class="sm:col-span-5">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Nama / Identitas Unit *</label>
                        <input type="text" name="vehicle_name" value="{{ old('vehicle_name', $fleet->vehicle_name ?? '') }}" required placeholder="Contoh: Prime Mover CNG Cradle 01" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm">
                    </div>
                </div>

                <!-- Baris 2: Tipe Armada (Dinamis), Merk, Tahun -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase">Tipe Armada *</label>
                            <a href="{{ route('admin.fleets.types.index') }}" target="_blank" class="text-[11px] font-bold text-brand-600 hover:underline flex items-center gap-1" title="Buka kelola tipe armada di tab baru">
                                <i class="fa-solid fa-plus-circle"></i> Tambah Tipe
                            </a>
                        </div>
                        <select name="type" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm">
                            <option value="">-- Pilih Tipe Armada --</option>
                            @if(isset($fleetTypes) && $fleetTypes->count() > 0)
                                @foreach($fleetTypes as $ft)
                                <option value="{{ $ft->name }}" {{ old('type', $fleet->type ?? '') == $ft->name ? 'selected' : '' }}>
                                    {{ $ft->name }}
                                </option>
                                @endforeach
                            @else
                                <option value="CNG Tube Trailer" {{ old('type', $fleet->type ?? '') == 'CNG Tube Trailer' ? 'selected' : '' }}>CNG Tube Trailer</option>
                                <option value="Box Truck" {{ old('type', $fleet->type ?? '') == 'Box Truck' ? 'selected' : '' }}>Box Truck</option>
                                <option value="Heavy Box Truck" {{ old('type', $fleet->type ?? '') == 'Heavy Box Truck' ? 'selected' : '' }}>Heavy Box Truck</option>
                                <option value="Flatbed Trailer" {{ old('type', $fleet->type ?? '') == 'Flatbed Trailer' ? 'selected' : '' }}>Flatbed Trailer</option>
                                <option value="Tangki Khusus" {{ old('type', $fleet->type ?? '') == 'Tangki Khusus' ? 'selected' : '' }}>Tangki Khusus</option>
                            @endif
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

                <!-- Baris 3: Kapasitas, Status, Supir -->
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

                <!-- Tanggal Jatuh Tempo Legalitas -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-200">
                    <div>
                        <label class="block text-xs font-bold text-navy-900 uppercase mb-1.5">
                            <i class="fa-solid fa-calendar-check text-brand-600 mr-1"></i> Tanggal Jatuh Tempo Uji KIR
                        </label>
                        <input type="date" name="kir_expiry" value="{{ old('kir_expiry', isset($fleet) && $fleet->kir_expiry ? $fleet->kir_expiry->format('Y-m-d') : '') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm bg-white">
                        <span class="text-[10px] text-slate-500 mt-1 block">Peringatan otomatis aktif jika sisa &lt; 30 hari.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-navy-900 uppercase mb-1.5">
                            <i class="fa-solid fa-calendar-days text-brand-600 mr-1"></i> Tanggal Jatuh Tempo Pajak STNK
                        </label>
                        <input type="date" name="stnk_expiry" value="{{ old('stnk_expiry', isset($fleet) && $fleet->stnk_expiry ? $fleet->stnk_expiry->format('Y-m-d') : '') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 text-sm bg-white">
                        <span class="text-[10px] text-slate-500 mt-1 block">Untuk pembaruan STNK tahunan / 5 tahunan.</span>
                    </div>
                </div>

                <!-- UPLOAD BERKAS DOKUMEN FISIK: STNK, BPKB, KIR (OPSIONAL) -->
                <div class="p-5 rounded-2xl bg-brand-50/40 border border-brand-200/80 space-y-4">
                    <div class="border-b border-brand-100 pb-2 flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-xs uppercase tracking-wider text-navy-900 flex items-center gap-2">
                                <i class="fa-solid fa-folder-open text-brand-600"></i>
                                <span>Upload Dokumen Legalitas Kendaraan (Opsional)</span>
                            </h3>
                            <p class="text-[11px] text-slate-500">Unggah berkas digital untuk kemudahan arsip & pemeriksaan (Format: PDF, JPG, PNG maks 5MB).</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Berkas STNK -->
                        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-bold text-slate-800 uppercase">
                                    <i class="fa-solid fa-id-card text-brand-600 mr-1"></i> Berkas STNK
                                </label>
                                @if(isset($fleet) && $fleet->stnk_file)
                                <a href="{{ asset($fleet->stnk_file) }}" target="_blank" class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 flex items-center gap-1 hover:underline">
                                    <i class="fa-solid fa-check-circle"></i> Lihat File
                                </a>
                                @endif
                            </div>
                            <input type="file" name="stnk_file" accept=".pdf,image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                            <span class="text-[10px] text-slate-400 block">Surat Tanda Nomor Kendaraan</span>
                        </div>

                        <!-- Berkas BPKB -->
                        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-bold text-slate-800 uppercase">
                                    <i class="fa-solid fa-book-bookmark text-amber-600 mr-1"></i> Berkas BPKB
                                </label>
                                @if(isset($fleet) && $fleet->bpkb_file)
                                <a href="{{ asset($fleet->bpkb_file) }}" target="_blank" class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 flex items-center gap-1 hover:underline">
                                    <i class="fa-solid fa-check-circle"></i> Lihat File
                                </a>
                                @endif
                            </div>
                            <input type="file" name="bpkb_file" accept=".pdf,image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                            <span class="text-[10px] text-slate-400 block">Buku Kepemilikan Kendaraan Bermotor</span>
                        </div>

                        <!-- Berkas KIR -->
                        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-bold text-slate-800 uppercase">
                                    <i class="fa-solid fa-file-shield text-blue-600 mr-1"></i> Berkas KIR
                                </label>
                                @if(isset($fleet) && $fleet->kir_file)
                                <a href="{{ asset($fleet->kir_file) }}" target="_blank" class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 flex items-center gap-1 hover:underline">
                                    <i class="fa-solid fa-check-circle"></i> Lihat File
                                </a>
                                @endif
                            </div>
                            <input type="file" name="kir_file" accept=".pdf,image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                            <span class="text-[10px] text-slate-400 block">Buku Uji Berkala / Kartu Uji KIR</span>
                        </div>
                    </div>
                </div>

                <!-- Foto Kendaraan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Foto Kendaraan</label>
                    @if(isset($fleet) && $fleet->image_path)
                    <div class="mb-3 flex items-center gap-3">
                        <img src="{{ asset($fleet->image_path) }}" class="w-24 h-16 object-cover rounded-xl border border-slate-200 shadow-sm">
                        <span class="text-xs text-slate-500">Foto saat ini terpasang.</span>
                    </div>
                    @endif
                    <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                </div>

                <!-- Catatan Operasional -->
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
