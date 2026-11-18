@extends('layouts.admin')

@section('title', 'Dashboard Manajemen Armada')
@section('page_title', 'Dashboard Armada: Data Kendaraan & Pengawasan Legalitas')

@section('content')

    <div class="space-y-6">
        <!-- Action & Search Filter Bar -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-navy-900">Armada Truk Operasional</h2>
                <p class="text-xs text-slate-500">Pantau nomor urut, ketersediaan unit, pengemudi, serta kelengkapan berkas fisik STNK, BPKB & KIR.</p>
            </div>
            <div class="flex items-center gap-2.5 flex-wrap">
                <!-- Tombol Cetak Tabel -->
                <a href="{{ route('admin.fleets.report', request()->query()) }}" target="_blank" class="px-4 py-2.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs uppercase tracking-wider transition flex items-center gap-2 shadow-sm" title="Buka format cetak / PDF rekapitulasi data armada">
                    <i class="fa-solid fa-print text-brand-600"></i>
                    <span>Cetak Tabel</span>
                </a>

                <!-- Tombol Menu Tipe Armada -->
                <a href="{{ route('admin.fleets.types.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs uppercase tracking-wider transition flex items-center gap-2 shadow-sm" title="Kelola kategori dan tambah tipe armada baru">
                    <i class="fa-solid fa-tags text-amber-600"></i>
                    <span>Tipe Armada</span>
                </a>

                <!-- Tombol Tambah Unit -->
                <a href="{{ route('admin.fleets.create') }}" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-brand-600/20 transition flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Tambah Unit Truk</span>
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if(session('warning'))
        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-triangle-exclamation text-amber-600 text-sm"></i>
            <span>{{ session('warning') }}</span>
        </div>
        @endif

        <!-- Filter Controls & View Switcher -->
        <div class="bg-white p-4 rounded-3xl border border-slate-200 shadow-sm space-y-3">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 border-b border-slate-100 pb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-600">Filter & Pilihan Tampilan</span>

                <!-- View Switcher Toggle -->
                <div class="inline-flex p-1 rounded-xl bg-slate-100 border border-slate-200 self-start md:self-auto">
                    <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}" class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 {{ $view !== 'table' ? 'bg-white text-navy-900 shadow-sm' : 'text-slate-500 hover:text-navy-900' }}">
                        <i class="fa-solid fa-grip text-xs"></i>
                        <span>Tampilan Kartu (Grid)</span>
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['view' => 'table']) }}" class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 {{ $view === 'table' ? 'bg-white text-navy-900 shadow-sm' : 'text-slate-500 hover:text-navy-900' }}">
                        <i class="fa-solid fa-table-list text-xs"></i>
                        <span>Tampilan Tabel</span>
                    </a>
                </div>
            </div>

            <form action="{{ route('admin.fleets.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                <input type="hidden" name="view" value="{{ $view }}">

                <div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Plat / Supir / Unit / No. Urut..." class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-600">
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
                    <select name="type" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-600">
                        <option value="">-- Semua Tipe --</option>
                        @foreach($types as $t)
                        <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
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
                    <a href="{{ route('admin.fleets.index', ['view' => $view]) }}" class="px-3 py-2 rounded-xl bg-slate-100 text-slate-600 text-xs font-semibold hover:bg-slate-200 transition" title="Reset Filter">
                        <i class="fa-solid fa-arrows-rotate"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- ==================== TAMPILAN TABEL ==================== -->
        @if($view === 'table')
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-xs uppercase tracking-wider text-navy-900">Tabel Data Rekapitulasi Armada</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-brand-50 text-brand-700 border border-brand-200">Total: {{ $fleets->total() }} Unit</span>
                </div>
                <span class="text-[11px] text-slate-400">Diurutkan berdasarkan Nomor Urut</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-100/70 text-slate-700 uppercase font-bold text-[11px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-4 w-14 text-center">No. Urut</th>
                            <th class="py-3.5 px-4">Kendaraan & Plat Nomor</th>
                            <th class="py-3.5 px-4">Tipe Armada</th>
                            <th class="py-3.5 px-4">Kapasitas</th>
                            <th class="py-3.5 px-4">Pengemudi (Driver)</th>
                            <th class="py-3.5 px-4 text-center">Kelengkapan Berkas</th>
                            <th class="py-3.5 px-4">Jatuh Tempo KIR & STNK</th>
                            <th class="py-3.5 px-4 text-center">Status</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($fleets as $fleet)
                        @php
                            $kirInfo = $fleet->kir_status;
                            $stnkInfo = $fleet->stnk_status;
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition">
                            <!-- No Urut -->
                            <td class="py-3.5 px-4 text-center font-mono font-bold text-sm text-navy-900">
                                <span class="w-8 h-8 rounded-lg bg-slate-100 inline-flex items-center justify-center border border-slate-200">
                                    {{ $fleet->order_number ?? '-' }}
                                </span>
                            </td>

                            <!-- Kendaraan & Plat -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-10 rounded-lg overflow-hidden bg-slate-100 flex-shrink-0 border border-slate-200">
                                        <img src="{{ asset($fleet->image_path ?? '/images/truck-cng-green.jpg') }}" alt="{{ $fleet->vehicle_name }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <span class="font-mono font-bold text-xs text-navy-900 block bg-slate-100 px-1.5 py-0.5 rounded w-max border border-slate-200 mb-0.5">
                                            {{ $fleet->plate_number }}
                                        </span>
                                        <span class="font-bold text-xs text-slate-800 block">{{ $fleet->vehicle_name }}</span>
                                        <span class="text-[11px] text-slate-400">{{ $fleet->brand ?? '-' }} &bull; Thn {{ $fleet->year ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Tipe Armada -->
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200 whitespace-nowrap">
                                    {{ $fleet->type }}
                                </span>
                            </td>

                            <!-- Kapasitas -->
                            <td class="py-3.5 px-4 font-semibold text-slate-700">
                                {{ $fleet->capacity ?? '-' }}
                            </td>

                            <!-- Driver -->
                            <td class="py-3.5 px-4">
                                <span class="font-medium text-slate-800 block">{{ $fleet->driver_name ?? 'Belum Ditugaskan' }}</span>
                            </td>

                            <!-- Kelengkapan Berkas (STNK, BPKB, KIR) -->
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- STNK -->
                                    @if($fleet->stnk_file)
                                    <a href="{{ asset($fleet->stnk_file) }}" target="_blank" class="w-6 h-6 rounded bg-emerald-100 text-emerald-800 inline-flex items-center justify-center text-[10px] font-bold" title="Berkas STNK Tersedia (Klik untuk melihat)">
                                        S
                                    </a>
                                    @else
                                    <span class="w-6 h-6 rounded bg-slate-100 text-slate-300 inline-flex items-center justify-center text-[10px]" title="STNK Belum Diunggah">
                                        S
                                    </span>
                                    @endif

                                    <!-- BPKB -->
                                    @if($fleet->bpkb_file)
                                    <a href="{{ asset($fleet->bpkb_file) }}" target="_blank" class="w-6 h-6 rounded bg-amber-100 text-amber-800 inline-flex items-center justify-center text-[10px] font-bold" title="Berkas BPKB Tersedia (Klik untuk melihat)">
                                        B
                                    </a>
                                    @else
                                    <span class="w-6 h-6 rounded bg-slate-100 text-slate-300 inline-flex items-center justify-center text-[10px]" title="BPKB Belum Diunggah">
                                        B
                                    </span>
                                    @endif

                                    <!-- KIR -->
                                    @if($fleet->kir_file)
                                    <a href="{{ asset($fleet->kir_file) }}" target="_blank" class="w-6 h-6 rounded bg-blue-100 text-blue-800 inline-flex items-center justify-center text-[10px] font-bold" title="Berkas KIR Tersedia (Klik untuk melihat)">
                                        K
                                    </a>
                                    @else
                                    <span class="w-6 h-6 rounded bg-slate-100 text-slate-300 inline-flex items-center justify-center text-[10px]" title="KIR Belum Diunggah">
                                        K
                                    </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Jatuh Tempo KIR & STNK -->
                            <td class="py-3.5 px-4 text-[11px]">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-slate-400 w-10">KIR:</span>
                                        <span class="font-semibold {{ $kirInfo['status'] === 'expired' ? 'text-rose-600 font-bold' : ($kirInfo['status'] === 'warning' ? 'text-amber-600 font-bold' : 'text-slate-700') }}">
                                            {{ $fleet->kir_expiry ? $fleet->kir_expiry->format('d/m/Y') : '-' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-slate-400 w-10">STNK:</span>
                                        <span class="font-semibold {{ $stnkInfo['status'] === 'expired' ? 'text-rose-600 font-bold' : ($stnkInfo['status'] === 'warning' ? 'text-amber-600 font-bold' : 'text-slate-700') }}">
                                            {{ $fleet->stnk_expiry ? $fleet->stnk_expiry->format('d/m/Y') : '-' }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $fleet->status === 'Tersedia' ? 'bg-emerald-500 text-white' : ($fleet->status === 'Dalam Perjalanan' ? 'bg-blue-500 text-white' : ($fleet->status === 'Perawatan' ? 'bg-amber-500 text-white' : 'bg-slate-400 text-white')) }}">
                                    {{ $fleet->status }}
                                </span>
                            </td>

                            <!-- Aksi -->
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.fleets.show', $fleet->id) }}" class="p-2 rounded-lg bg-slate-100 hover:bg-brand-50 text-slate-600 hover:text-brand-600 transition" title="Lihat Profil & Servis">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('admin.fleets.edit', $fleet->id) }}" class="p-2 rounded-lg bg-slate-100 hover:bg-brand-50 text-slate-600 hover:text-brand-600 transition" title="Edit Data & Dokumen">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.fleets.destroy', $fleet->id) }}" method="POST" onsubmit="return confirm('Hapus unit armada {{ $fleet->plate_number }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-400 hover:text-rose-600 transition" title="Hapus Unit">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-truck-moving text-3xl text-slate-300 mb-2 block"></i>
                                Tidak ada data armada yang sesuai dengan filter pencarian.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($fleets->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $fleets->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
        @endif

        <!-- ==================== TAMPILAN GRID (KARTU) ==================== -->
        @if($view !== 'table')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($fleets as $fleet)
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <!-- Image Header -->
                    <div class="h-44 relative overflow-hidden bg-slate-100">
                        <img src="{{ asset($fleet->image_path ?? '/images/truck-cng-green.jpg') }}" alt="{{ $fleet->vehicle_name }}" class="w-full h-full object-cover">

                        <!-- Badge No. Urut & Plat Nomor -->
                        <div class="absolute top-3 left-3 flex items-center gap-1.5">
                            <span class="bg-brand-600 text-white font-mono font-bold text-xs px-2 py-1 rounded-md shadow-sm">
                                #{{ $fleet->order_number ?? '-' }}
                            </span>
                            <span class="bg-navy-900/95 text-white font-mono font-bold text-xs px-2.5 py-1 rounded-md border border-slate-700 shadow-sm">
                                {{ $fleet->plate_number }}
                            </span>
                        </div>

                        <div class="absolute top-3 right-3">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold shadow-sm {{ $fleet->status === 'Tersedia' ? 'bg-emerald-500 text-white' : ($fleet->status === 'Dalam Perjalanan' ? 'bg-blue-500 text-white' : ($fleet->status === 'Perawatan' ? 'bg-amber-500 text-white' : 'bg-slate-500 text-white')) }}">
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

                        <!-- Kelengkapan Berkas STNK, BPKB, KIR -->
                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-slate-500">Berkas Dokumen:</span>
                            <div class="flex items-center gap-1.5">
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold {{ $fleet->stnk_file ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-400' }}" title="{{ $fleet->stnk_file ? 'STNK Terunggah' : 'STNK Belum Ada' }}">STNK</span>
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold {{ $fleet->bpkb_file ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-400' }}" title="{{ $fleet->bpkb_file ? 'BPKB Terunggah' : 'BPKB Belum Ada' }}">BPKB</span>
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold {{ $fleet->kir_file ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-400' }}" title="{{ $fleet->kir_file ? 'KIR Terunggah' : 'KIR Belum Ada' }}">KIR</span>
                            </div>
                        </div>

                        <!-- Legalities Warning Badges -->
                        <div class="pt-1 space-y-1.5">
                            @php
                                $kirInfo = $fleet->kir_status;
                                $stnkInfo = $fleet->stnk_status;
                            @endphp
                            <div class="flex items-center justify-between p-2 rounded-xl border text-[11px] font-semibold {{ $kirInfo['class'] }}">
                                <span>Uji KIR:</span>
                                <span>{{ $kirInfo['label'] }}</span>
                            </div>
                            <div class="flex items-center justify-between p-2 rounded-xl border text-[11px] font-semibold {{ $stnkInfo['class'] }}">
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
                        <a href="{{ route('admin.fleets.edit', $fleet->id) }}" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-brand-600 text-xs shadow-sm" title="Edit Unit">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <form action="{{ route('admin.fleets.destroy', $fleet->id) }}" method="POST" onsubmit="return confirm('Hapus unit armada {{ $fleet->plate_number }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 rounded-xl bg-white border border-slate-200 text-rose-600 hover:text-rose-800 text-xs shadow-sm" title="Hapus Unit">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-12 text-center bg-white rounded-3xl border border-slate-200">
                <i class="fa-solid fa-truck-moving text-4xl text-slate-300 mb-3 block"></i>
                <h4 class="font-bold text-sm text-slate-700">Tidak ada armada ditemukan</h4>
                <p class="text-xs text-slate-500 mt-1">Coba sesuaikan kata kunci pencarian atau tambah unit baru.</p>
            </div>
            @endforelse
        </div>

        @if($fleets->hasPages())
        <div class="pt-4">
            {{ $fleets->appends(request()->query())->links() }}
        </div>
        @endif
        @endif

    </div>

@endsection
