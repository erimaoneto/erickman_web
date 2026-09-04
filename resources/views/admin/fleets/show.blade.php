@extends('layouts.admin')

@section('title', 'Detail Armada ' . $fleet->plate_number)
@section('page_title', 'Profil Armada: ' . $fleet->plate_number)

@section('content')

    <div class="space-y-8" x-data="{ maintenanceModal: false }">
        <!-- Header & Action -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.fleets.index') }}" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-navy-900 text-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <div class="flex items-center gap-3">
                        <h2 class="text-2xl font-black text-navy-900 font-mono">{{ $fleet->plate_number }}</h2>
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $fleet->status === 'Tersedia' ? 'bg-emerald-500 text-white' : ($fleet->status === 'Dalam Perjalanan' ? 'bg-blue-500 text-white' : 'bg-amber-500 text-white') }}">
                            {{ $fleet->status }}
                        </span>
                    </div>
                    <span class="text-xs text-slate-500">{{ $fleet->vehicle_name }} &bull; {{ $fleet->type }}</span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button @click="maintenanceModal = true" class="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-brand-600/20 transition flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Catat Servis Baru</span>
                </button>
                <a href="{{ route('admin.fleets.edit', $fleet->id) }}" class="px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-navy-900 font-bold text-xs uppercase tracking-wider transition">
                    <i class="fa-solid fa-pen mr-1"></i> Edit Unit
                </a>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Image & Driver Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden space-y-4">
                <div class="h-56 bg-slate-900 relative">
                    <img src="{{ asset($fleet->image_path ?? '/images/truck-cng-green.jpg') }}" alt="{{ $fleet->vehicle_name }}" class="w-full h-full object-cover">
                </div>
                <div class="p-6 pt-0 space-y-4">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Pengemudi (Driver):</span>
                        <h4 class="text-base font-bold text-navy-900 mt-0.5">{{ $fleet->driver_name ?? 'Belum Ditugaskan' }}</h4>
                    </div>

                    <div class="space-y-2 pt-3 border-t border-slate-100 text-xs">
                        <div class="flex justify-between py-1">
                            <span class="text-slate-500">Merk / Model:</span>
                            <span class="font-semibold text-slate-800">{{ $fleet->brand ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-slate-500">Tahun Armada:</span>
                            <span class="font-semibold text-slate-800">{{ $fleet->year ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-slate-500">Kapasitas Muatan:</span>
                            <span class="font-semibold text-slate-800">{{ $fleet->capacity ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-slate-500">Total Biaya Servis:</span>
                            <span class="font-bold text-rose-600">Rp {{ number_format($totalMaintenanceCost, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Legalities & Status Cards -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <h3 class="font-bold text-base text-navy-900">Status Legalitas Uji KIR & Pajak STNK</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @php
                            $kir = $fleet->kir_status;
                            $stnk = $fleet->stnk_status;
                        @endphp
                        <div class="p-4 rounded-xl border {{ $kir['class'] }} space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-xs uppercase tracking-wider">Uji Berkala (KIR)</span>
                                <i class="fa-solid fa-file-shield text-base"></i>
                            </div>
                            <div class="text-lg font-black">
                                {{ $fleet->kir_expiry ? $fleet->kir_expiry->format('d M Y') : 'Belum Ada Data' }}
                            </div>
                            <p class="text-xs">{{ $kir['label'] }}</p>
                        </div>

                        <div class="p-4 rounded-xl border {{ $stnk['class'] }} space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-xs uppercase tracking-wider">Pajak Kendaraan (STNK)</span>
                                <i class="fa-solid fa-id-card text-base"></i>
                            </div>
                            <div class="text-lg font-black">
                                {{ $fleet->stnk_expiry ? $fleet->stnk_expiry->format('d M Y') : 'Belum Ada Data' }}
                            </div>
                            <p class="text-xs">{{ $stnk['label'] }}</p>
                        </div>
                    </div>

                    @if($fleet->notes)
                    <div class="pt-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">Catatan Operasional:</span>
                        <p class="text-xs text-slate-700 bg-slate-50 p-3.5 rounded-xl border border-slate-200/80 leading-relaxed">{{ $fleet->notes }}</p>
                    </div>
                    @endif
                </div>

                <!-- Maintenance History Table -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="font-bold text-base text-navy-900">Riwayat Servis & Perawatan (Maintenance Logs)</h3>
                        <span class="text-xs text-slate-500">{{ $fleet->maintenances->count() }} Catatan</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-slate-600">
                            <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[11px] tracking-wider border-b border-slate-200">
                                <tr>
                                    <th class="py-3 px-4">Tanggal</th>
                                    <th class="py-3 px-4">Jenis Servis</th>
                                    <th class="py-3 px-4">Bengkel / Workshop</th>
                                    <th class="py-3 px-4">Odometer</th>
                                    <th class="py-3 px-4 text-right">Biaya (IDR)</th>
                                    <th class="py-3 px-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($fleet->maintenances as $m)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="py-3.5 px-4 font-semibold text-slate-800">{{ $m->service_date->format('d/m/Y') }}</td>
                                    <td class="py-3.5 px-4">
                                        <span class="font-bold text-navy-900 block">{{ $m->service_type }}</span>
                                        @if($m->description)
                                        <span class="text-[11px] text-slate-400 line-clamp-1">{{ $m->description }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-700">{{ $m->workshop ?? '-' }}</td>
                                    <td class="py-3.5 px-4 font-mono">{{ $m->odometer_km ? number_format($m->odometer_km, 0, ',', '.') . ' km' : '-' }}</td>
                                    <td class="py-3.5 px-4 text-right font-bold text-rose-600">Rp {{ number_format($m->cost, 0, ',', '.') }}</td>
                                    <td class="py-3.5 px-4 text-right">
                                        <form action="{{ route('admin.fleets.maintenance.delete', $m->id) }}" method="POST" onsubmit="return confirm('Hapus catatan servis ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-500 hover:text-rose-700 text-xs">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-400">Belum ada riwayat servis untuk unit ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Tambah Servis -->
        <div x-show="maintenanceModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm">
            <div @click.away="maintenanceModal = false" class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full max-h-[90vh] overflow-y-auto space-y-4 shadow-2xl">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-base text-navy-900">Catat Pemeliharaan / Servis Unit {{ $fleet->plate_number }}</h3>
                    <button @click="maintenanceModal = false" class="text-slate-400 hover:text-slate-700"><i class="fa-solid fa-xmark text-lg"></i></button>
                </div>

                <form action="{{ route('admin.fleets.maintenance.store', $fleet->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Servis *</label>
                            <input type="date" name="service_date" value="{{ date('Y-m-d') }}" required class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Biaya (Rp) *</label>
                            <input type="number" name="cost" required placeholder="Contoh: 2500000" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jenis Pemeliharaan / Servis *</label>
                        <input type="text" name="service_type" required placeholder="Contoh: Kalibrasi Pipa CNG / Ganti Oli Mesin / Uji KIR" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Bengkel / Pool</label>
                            <input type="text" name="workshop" placeholder="Contoh: Auto2000 / Pool Simatupang" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Odometer (KM)</label>
                            <input type="number" name="odometer_km" placeholder="Contoh: 75000" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Keterangan / Part yang Diganti</label>
                        <textarea name="description" rows="3" placeholder="Rincian suku cadang atau perbaikan..." class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm"></textarea>
                    </div>

                    <div class="pt-4 flex justify-end gap-2">
                        <button @click="maintenanceModal = false" type="button" class="px-4 py-2 rounded-xl border border-slate-300 text-xs font-semibold text-slate-600">Batal</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow">Simpan Catatan Servis</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
