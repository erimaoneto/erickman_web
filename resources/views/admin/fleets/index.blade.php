@extends('layouts.admin')

@section('title', 'Dashboard Manajemen Armada')
@section('page_title', 'Dashboard Armada: Data Kendaraan & Pengawasan KIR')

@section('content')

    <div class="space-y-6">
        <!-- Action & Search Filter Bar -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-navy-900">Armada Truk Operasional</h2>
                <p class="text-xs text-slate-500">Pantau ketersediaan unit, pengemudi, serta masa berlaku uji KIR & STNK.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.fleets.create') }}" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-brand-600/20 transition flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Tambah Unit Truk</span>
                </a>
            </div>
        </div>

        <!-- Filter Controls -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
            <form action="{{ route('admin.fleets.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Plat / Supir / Unit..." class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-600">
                </div>

                <div>
                    <select name="status" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-600">
                        <option value="">-- Semua Status --</option>
                        <option value="Tersedia" {{ request('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="Dalam Perjalanan" {{ request('status') == 'Dalam Perjalanan' ? 'selected' : '' }}>Dalam Perjalanan</option>
                        <option value="Perawatan" {{ request('status') == 'Perawatan' ? 'selected' : '' }}>Perawatan / Servis</option>
                        <option value="Non-Aktif" {{ request('status') == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                </div>

                <div>
                    <select name="filter" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-600">
                        <option value="">-- Peringatan Legalitas --</option>
                        <option value="urgent_kir" {{ request('filter') == 'urgent_kir' ? 'selected' : '' }}>KIR Jatuh Tempo / Expired</option>
                        <option value="urgent_stnk" {{ request('filter') == 'urgent_stnk' ? 'selected' : '' }}>STNK Jatuh Tempo / Expired</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 py-2 rounded-xl bg-navy-900 text-white font-bold text-xs hover:bg-navy-800 transition">
                        Filter Data
                    </button>
                    <a href="{{ route('admin.fleets.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 text-slate-600 text-xs font-semibold hover:bg-slate-200 transition">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Fleet Grid Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($fleets as $fleet)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <!-- Image Header -->
                    <div class="h-44 relative overflow-hidden bg-slate-100">
                        <img src="{{ asset($fleet->image_path ?? '/images/truck-cng-green.jpg') }}" alt="{{ $fleet->vehicle_name }}" class="w-full h-full object-cover">
                        <div class="absolute top-3 left-3 bg-navy-900/90 text-white font-mono font-bold text-xs px-2.5 py-1 rounded border border-slate-700">
                            {{ $fleet->plate_number }}
                        </div>
                        <div class="absolute top-3 right-3">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $fleet->status === 'Tersedia' ? 'bg-emerald-500 text-white' : ($fleet->status === 'Dalam Perjalanan' ? 'bg-blue-500 text-white' : 'bg-amber-500 text-white') }}">
                                {{ $fleet->status }}
                            </span>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="p-5 space-y-3">
                        <div>
                            <span class="text-[10px] font-bold text-brand-600 uppercase tracking-wider block">{{ $fleet->type }}</span>
                            <h3 class="font-bold text-base text-navy-900">{{ $fleet->vehicle_name }}</h3>
                            <span class="text-xs text-slate-500">{{ $fleet->brand ?? 'Unit Truk' }} &bull; Thn {{ $fleet->year ?? '-' }}</span>
                        </div>

                        <div class="text-xs space-y-1.5 pt-2 border-t border-slate-100">
                            <div class="flex items-center justify-between text-slate-600">
                                <span>Pengemudi (Driver):</span>
                                <span class="font-semibold text-navy-900">{{ $fleet->driver_name ?? 'Belum Ditugaskan' }}</span>
                            </div>
                            <div class="flex items-center justify-between text-slate-600">
                                <span>Kapasitas:</span>
                                <span class="font-semibold text-navy-900">{{ $fleet->capacity ?? '-' }}</span>
                            </div>
                        </div>

                        <!-- Legalities Warning Badges -->
                        <div class="pt-2 space-y-1.5">
                            @php
                                $kirInfo = $fleet->kir_status;
                                $stnkInfo = $fleet->stnk_status;
                            @endphp
                            <div class="flex items-center justify-between p-2 rounded-lg border text-[11px] font-semibold {{ $kirInfo['class'] }}">
                                <span>Uji KIR:</span>
                                <span>{{ $kirInfo['label'] }}</span>
                            </div>
                            <div class="flex items-center justify-between p-2 rounded-lg border text-[11px] font-semibold {{ $stnkInfo['class'] }}">
                                <span>Pajak STNK:</span>
                                <span>{{ $stnkInfo['label'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ route('admin.fleets.show', $fleet->id) }}" class="text-xs font-bold text-brand-600 hover:text-brand-700 flex items-center gap-1.5">
                        <i class="fa-solid fa-screwdriver-wrench"></i>
                        <span>Riwayat Servis ({{ $fleet->maintenances_count }})</span>
                    </a>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.fleets.edit', $fleet->id) }}" class="p-2 rounded-lg bg-white border border-slate-200 text-slate-600 hover:text-brand-600 text-xs">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <form action="{{ route('admin.fleets.destroy', $fleet->id) }}" method="POST" onsubmit="return confirm('Hapus unit armada {{ $fleet->plate_number }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 rounded-lg bg-white border border-slate-200 text-rose-600 hover:text-rose-800 text-xs">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-slate-200">
                <i class="fa-solid fa-truck-moving text-4xl text-slate-300 mb-3"></i>
                <h4 class="font-bold text-sm text-slate-700">Tidak ada armada ditemukan</h4>
                <p class="text-xs text-slate-500 mt-1">Coba sesuaikan kata kunci pencarian atau tambah unit baru.</p>
            </div>
            @endforelse
        </div>

        <div class="pt-4">
            {{ $fleets->links() }}
        </div>
    </div>

@endsection
