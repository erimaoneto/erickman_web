@extends('layouts.admin')

@section('title', 'Data GTM (Gas Transport Module) - PT Erickman')

@section('content')
<div class="space-y-6">

    <!-- Header & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-brand-100 text-brand-800 border border-brand-200">
                    Gas Transport Module
                </span>
                <span class="text-xs text-slate-400">Total: {{ $stats['total'] }} Unit Tabung</span>
            </div>
            <h1 class="text-2xl font-bold text-navy-900 mt-1">Data GTM (Gas Transport Module)</h1>
            <p class="text-xs text-slate-500">Kelola inventaris tabung bertekanan tinggi CNG, merek, kapasitas, kode tabung, dan tahun pembuatan.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <!-- Tombol Cetak Data GTM -->
            <a href="{{ route('admin.gtm.report', request()->query()) }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-700 hover:text-navy-900 hover:border-slate-400 font-bold text-xs shadow-sm flex items-center gap-2 transition">
                <i class="fa-solid fa-print text-emerald-600"></i>
                <span>Cetak Data GTM</span>
            </a>

            <!-- Tombol Tambah Data GTM -->
            <a href="{{ route('admin.gtm.create') }}" class="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md shadow-brand-600/20 flex items-center gap-2 transition">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Tabung GTM</span>
            </a>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif

    <!-- Statistics Summary Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-slate-100 flex items-center justify-center text-navy-900 font-bold text-lg shrink-0">
                <i class="fa-solid fa-cubes-stacked text-brand-600"></i>
            </div>
            <div>
                <span class="text-xs text-slate-500 font-medium block">Total Tabung</span>
                <span class="text-xl font-bold text-navy-900">{{ $stats['total'] }} Unit</span>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg shrink-0">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <span class="text-xs text-slate-500 font-medium block">Aktif / Beroperasi</span>
                <span class="text-xl font-bold text-emerald-600">{{ $stats['aktif'] }} Unit</span>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg shrink-0">
                <i class="fa-solid fa-boxes-packing"></i>
            </div>
            <div>
                <span class="text-xs text-slate-500 font-medium block">Standby / Cadangan</span>
                <span class="text-xl font-bold text-blue-600">{{ $stats['standby'] }} Unit</span>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg shrink-0">
                <i class="fa-solid fa-certificate"></i>
            </div>
            <div>
                <span class="text-xs text-slate-500 font-medium block">Rekualifikasi / Uji</span>
                <span class="text-xl font-bold text-amber-600">{{ $stats['rekualifikasi'] }} Unit</span>
            </div>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="p-4 bg-white rounded-2xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('admin.gtm.index') }}" method="GET" class="flex flex-col md:flex-row items-center justify-between gap-3 text-xs">
            <div class="relative w-full md:w-80">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Kode Tabung, Merek, Kapasitas..." class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-300 text-xs bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-brand-600 focus:outline-none transition">
            </div>

            <div class="flex flex-wrap items-center gap-2 w-full md:w-auto justify-end">
                <select name="status" class="px-3 py-2.5 rounded-xl border border-slate-300 text-xs bg-white font-medium focus:ring-2 focus:ring-brand-600 focus:outline-none">
                    <option value="">-- Semua Status --</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="standby" {{ request('status') == 'standby' ? 'selected' : '' }}>Standby</option>
                    <option value="rekualifikasi" {{ request('status') == 'rekualifikasi' ? 'selected' : '' }}>Rekualifikasi</option>
                    <option value="perbaikan" {{ request('status') == 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                </select>

                <select name="fleet_id" class="px-3 py-2.5 rounded-xl border border-slate-300 text-xs bg-white font-medium focus:ring-2 focus:ring-brand-600 focus:outline-none">
                    <option value="">-- Semua Armada Terpasang --</option>
                    @foreach($fleets as $fleet)
                    <option value="{{ $fleet->id }}" {{ request('fleet_id') == $fleet->id ? 'selected' : '' }}>
                        {{ $fleet->plate_number }} - {{ $fleet->vehicle_name }}
                    </option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2.5 rounded-xl bg-navy-900 hover:bg-navy-800 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                    <i class="fa-solid fa-filter text-[11px]"></i>
                    <span>Filter</span>
                </button>

                @if(request()->hasAny(['search', 'status', 'fleet_id']))
                <a href="{{ route('admin.gtm.index') }}" class="px-3 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs transition">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data GTM Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        @if($gtms->isEmpty())
        <div class="p-12 text-center text-slate-500">
            <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                <i class="fa-solid fa-cubes-stacked text-2xl"></i>
            </div>
            <h3 class="font-bold text-navy-900 text-sm">Belum Ada Data GTM</h3>
            <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                Mulai catat inventaris tabung GTM dengan mengisi merek, kapasitas, kode tabung, dan tahun pembuatan.
            </p>
            <div class="mt-4">
                <a href="{{ route('admin.gtm.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow transition">
                    <i class="fa-solid fa-plus"></i>
                    <span>Tambah Tabung Pertama</span>
                </a>
            </div>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs divide-y divide-slate-200">
                <thead class="bg-slate-50/80 font-bold text-slate-700 uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="p-3.5 text-center w-16">No. Urut</th>
                        <th class="p-3.5">Kode Tabung</th>
                        <th class="p-3.5">Merek Tabung</th>
                        <th class="p-3.5">Kapasitas</th>
                        <th class="p-3.5">Tahun Pembuatan</th>
                        <th class="p-3.5">Unit Armada Terpasang</th>
                        <th class="p-3.5">Lokasi Tabung</th>
                        <th class="p-3.5 text-center">Status</th>
                        <th class="p-3.5 text-right w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                    @foreach($gtms as $gtm)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="p-3.5 text-center font-mono font-bold text-slate-400">
                            #{{ $gtm->order_number ?? '-' }}
                        </td>
                        <td class="p-3.5">
                            <span class="font-mono font-bold text-navy-900 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200">
                                {{ $gtm->tube_code }}
                            </span>
                        </td>
                        <td class="p-3.5 font-bold text-slate-900">
                            {{ $gtm->tube_brand }}
                        </td>
                        <td class="p-3.5 font-semibold text-brand-700">
                            {{ $gtm->capacity }}
                        </td>
                        <td class="p-3.5 font-mono text-slate-600">
                            {{ $gtm->manufacture_year }}
                        </td>
                        <td class="p-3.5">
                            @if($gtm->fleet)
                            <a href="{{ route('admin.fleets.show', $gtm->fleet->id) }}" class="inline-flex items-center gap-1.5 text-navy-900 hover:text-brand-600 font-bold transition">
                                <i class="fa-solid fa-truck-moving text-[11px] text-slate-400"></i>
                                <span>{{ $gtm->fleet->plate_number }}</span>
                                <span class="text-[10px] text-slate-400 font-normal">({{ $gtm->fleet->vehicle_name }})</span>
                            </a>
                            @else
                            <span class="text-slate-400 italic text-[11px]">Belum terpasang (Standby)</span>
                            @endif
                        </td>
                        <td class="p-3.5 whitespace-nowrap">
                            @if($gtm->location)
                            <span class="inline-flex items-center gap-1.5 text-slate-700 font-medium">
                                <i class="fa-solid fa-location-dot text-rose-500 text-[10px]"></i>
                                <span>{{ $gtm->location }}</span>
                            </span>
                            @else
                            <span class="text-slate-400 italic text-[11px]">-</span>
                            @endif
                        </td>
                        <td class="p-3.5 text-center">
                            @if($gtm->status === 'aktif')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    Aktif
                                </span>
                            @elseif($gtm->status === 'standby')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                    Standby
                                </span>
                            @elseif($gtm->status === 'rekualifikasi')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                    Rekualifikasi
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                    {{ ucfirst($gtm->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="p-3.5 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.gtm.edit', $gtm->id) }}" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-brand-600 hover:text-white text-slate-600 flex items-center justify-center transition" title="Edit Data GTM">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </a>
                                <form action="{{ route('admin.gtm.destroy', $gtm->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data tabung GTM {{ $gtm->tube_code }}?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-rose-600 hover:text-white text-slate-600 flex items-center justify-center transition" title="Hapus Data GTM">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($gtms->hasPages())
        <div class="p-4 border-t border-slate-200">
            {{ $gtms->links() }}
        </div>
        @endif
        @endif
    </div>

</div>
@endsection
