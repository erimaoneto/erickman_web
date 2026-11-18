<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Data Armada - PT Erickman Sarana Abadi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; font-size: 10pt !important; padding: 0 !important; }
            .print-container { border: none !important; box-shadow: none !important; padding: 0 !important; max-width: 100% !important; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
            thead { display: table-header-group; }
        }
    </style>
</head>
<body class="bg-slate-100 p-4 sm:p-10 font-sans text-slate-800">

    <div class="print-container max-w-6xl mx-auto bg-white p-6 sm:p-10 rounded-2xl shadow-sm border border-slate-200">
        
        <!-- Print Header & Controls (Hidden when printing) -->
        <div class="no-print space-y-4 pb-6 mb-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <a href="{{ route('admin.fleets.index', ['view' => 'table']) }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Kembali ke Kelola Armada (Tampilan Tabel)</span>
                </a>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.finance.signatures') }}" target="_blank" class="px-4 py-2 rounded-xl bg-white border border-slate-300 text-slate-700 hover:text-slate-900 font-bold text-xs shadow-sm flex items-center gap-2 transition">
                        <i class="fa-solid fa-signature text-amber-600"></i>
                        <span>Atur Penanda Tangan</span>
                    </a>
                    <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow flex items-center justify-center gap-2">
                        <i class="fa-solid fa-print"></i>
                        <span>Cetak / Simpan PDF</span>
                    </button>
                </div>
            </div>

            <!-- Filter Controls -->
            <form action="{{ route('admin.fleets.report') }}" method="GET" class="p-4 bg-slate-50 rounded-xl border border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-2 font-bold text-slate-700">
                    <i class="fa-solid fa-filter text-amber-600"></i>
                    <span>Filter Cetak Armada:</span>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <select name="status" class="px-3 py-2 rounded-lg border border-slate-300 text-xs bg-white font-medium">
                        <option value="">-- Semua Status --</option>
                        <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia (Ready)</option>
                        <option value="perjalanan" {{ request('status') == 'perjalanan' ? 'selected' : '' }}>Dalam Perjalanan</option>
                        <option value="perawatan" {{ request('status') == 'perawatan' ? 'selected' : '' }}>Perawatan / Servis</option>
                        <option value="non-aktif" {{ request('status') == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>

                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nopol / Unit / Driver..." class="px-3 py-2 rounded-lg border border-slate-300 text-xs bg-white w-48">

                    <button type="submit" class="px-4 py-2 rounded-lg bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                        <i class="fa-solid fa-arrows-rotate text-[10px]"></i>
                        <span>Terapkan</span>
                    </button>
                    @if(request()->hasAny(['status', 'search', 'filter', 'type']))
                    <a href="{{ route('admin.fleets.report') }}" class="px-3 py-2 rounded-lg bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs transition">
                        Reset
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Corporate Letterhead -->
        <div class="flex items-start justify-between pb-6 border-b-2 border-slate-900 gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">PT. ERICKMAN SARANA ABADI</h1>
                <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold">Transportasi Gas Alam (CNG) & Equipment</p>
                <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                    18 Office Park Building Lt. 12 Unit A & H, Jl. TB Simatupang No.18, Jakarta Selatan 12520<br>
                    NIB: 2211210015706 &bull; Email: info@erickman.co.id &bull; Telp: +622130931188
                </p>
            </div>
            <div class="text-right shrink-0">
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Laporan Resmi Operasional</span>
                <span class="block text-lg font-bold text-slate-900 mt-0.5">
                    REKAPITULASI DATA ARMADA
                </span>
                <span class="text-[10px] text-slate-400 block mt-1">Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</span>
            </div>
        </div>

        <!-- Summary Totals -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 my-6 text-center text-xs">
            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200">
                <span class="text-[11px] font-semibold text-slate-500 block">Total Armada</span>
                <span class="text-lg font-bold text-slate-900 block mt-0.5">{{ $fleets->count() }} Unit</span>
            </div>
            <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200">
                <span class="text-[11px] font-semibold text-emerald-700 block">Tersedia (Ready)</span>
                <span class="text-lg font-bold text-emerald-800 block mt-0.5">{{ $fleets->where('status', 'tersedia')->count() }} Unit</span>
            </div>
            <div class="p-3 rounded-xl bg-blue-50 border border-blue-200">
                <span class="text-[11px] font-semibold text-blue-700 block">Dalam Perjalanan</span>
                <span class="text-lg font-bold text-blue-800 block mt-0.5">{{ $fleets->where('status', 'perjalanan')->count() }} Unit</span>
            </div>
            <div class="p-3 rounded-xl bg-amber-50 border border-amber-200">
                <span class="text-[11px] font-semibold text-amber-700 block">Perawatan (Servis)</span>
                <span class="text-lg font-bold text-amber-800 block mt-0.5">{{ $fleets->where('status', 'perawatan')->count() }} Unit</span>
            </div>
            <div class="p-3 rounded-xl bg-rose-50 border border-rose-200">
                <span class="text-[11px] font-semibold text-rose-700 block">Non-Aktif</span>
                <span class="text-lg font-bold text-rose-800 block mt-0.5">{{ $fleets->where('status', 'non-aktif')->count() }} Unit</span>
            </div>
        </div>

        <!-- Armada Table -->
        @if($fleets->isEmpty())
        <div class="p-8 text-center my-6 rounded-xl bg-slate-50 border border-dashed border-slate-300 text-slate-500">
            <i class="fa-solid fa-truck text-3xl text-slate-400 mb-2 block"></i>
            <p class="font-semibold text-sm text-slate-700">Tidak ada armada yang sesuai dengan kriteria filter.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-[11px] border border-slate-300 divide-y divide-slate-200">
                <thead class="bg-slate-100 font-bold text-slate-800 uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="p-2 border-r border-slate-300 text-center w-8">No</th>
                        <th class="p-2 border-r border-slate-300 text-center w-12">No. Urut</th>
                        <th class="p-2 border-r border-slate-300">No. Polisi</th>
                        <th class="p-2 border-r border-slate-300">Nama Unit</th>
                        <th class="p-2 border-r border-slate-300">Tipe Armada</th>
                        <th class="p-2 border-r border-slate-300">Merk / Model</th>
                        <th class="p-2 border-r border-slate-300">Kapasitas</th>
                        <th class="p-2 border-r border-slate-300">Pengemudi (Driver)</th>
                        <th class="p-2 border-r border-slate-300 text-center">Kelengkapan Dokumen</th>
                        <th class="p-2 border-r border-slate-300">Jatuh Tempo KIR</th>
                        <th class="p-2 border-r border-slate-300">Jatuh Tempo STNK</th>
                        <th class="p-2 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-700">
                    @foreach($fleets as $index => $fleet)
                    @php
                        $kirExpired = $fleet->kir_expiry && $fleet->kir_expiry->isPast();
                        $stnkExpired = $fleet->stnk_expiry && $fleet->stnk_expiry->isPast();
                    @endphp
                    <tr class="hover:bg-slate-50">
                        <td class="p-2 border-r border-slate-200 text-center font-mono">{{ $index + 1 }}</td>
                        <td class="p-2 border-r border-slate-200 text-center font-mono font-bold text-slate-900">
                            #{{ $fleet->order_number ?? '-' }}
                        </td>
                        <td class="p-2 border-r border-slate-200 font-mono font-bold text-slate-900 whitespace-nowrap">
                            {{ $fleet->plate_number }}
                        </td>
                        <td class="p-2 border-r border-slate-200 font-semibold text-slate-900">
                            {{ $fleet->vehicle_name }}
                        </td>
                        <td class="p-2 border-r border-slate-200 whitespace-nowrap">
                            {{ $fleet->type }}
                        </td>
                        <td class="p-2 border-r border-slate-200 whitespace-nowrap">
                            {{ $fleet->brand_model ?? '-' }} ({{ $fleet->year ?? '-' }})
                        </td>
                        <td class="p-2 border-r border-slate-200 whitespace-nowrap">
                            {{ $fleet->capacity ?? '-' }}
                        </td>
                        <td class="p-2 border-r border-slate-200 whitespace-nowrap">
                            {{ $fleet->driver_name ?? '-' }}
                        </td>
                        <td class="p-2 border-r border-slate-200 text-center font-mono text-[10px]">
                            <span class="{{ $fleet->stnk_file ? 'text-emerald-700 font-bold' : 'text-slate-300' }}">STNK: {{ $fleet->stnk_file ? 'ADA' : '-' }}</span> |
                            <span class="{{ $fleet->bpkb_file ? 'text-blue-700 font-bold' : 'text-slate-300' }}">BPKB: {{ $fleet->bpkb_file ? 'ADA' : '-' }}</span> |
                            <span class="{{ $fleet->kir_file ? 'text-amber-700 font-bold' : 'text-slate-300' }}">KIR: {{ $fleet->kir_file ? 'ADA' : '-' }}</span>
                        </td>
                        <td class="p-2 border-r border-slate-200 whitespace-nowrap font-mono {{ $kirExpired ? 'text-rose-600 font-bold' : '' }}">
                            {{ $fleet->kir_expiry ? $fleet->kir_expiry->format('d/m/Y') : '-' }}
                        </td>
                        <td class="p-2 border-r border-slate-200 whitespace-nowrap font-mono {{ $stnkExpired ? 'text-rose-600 font-bold' : '' }}">
                            {{ $fleet->stnk_expiry ? $fleet->stnk_expiry->format('d/m/Y') : '-' }}
                        </td>
                        <td class="p-2 text-center whitespace-nowrap">
                            @if($fleet->status == 'tersedia')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Tersedia</span>
                            @elseif($fleet->status == 'perjalanan')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800">Perjalanan</span>
                            @elseif($fleet->status == 'perawatan')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">Perawatan</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">{{ ucfirst($fleet->status) }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Signatures Section -->
        <div class="mt-12 pt-6 border-t border-slate-300">
            <div class="text-right text-xs text-slate-600 mb-8 font-medium">
                {{ $settings['report_city'] ?? 'Jakarta' }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
            </div>
            <div class="grid grid-cols-2 gap-8 text-xs text-center">
                <div>
                    <span class="text-slate-500 block mb-14 font-medium">{{ $settings['report_signer_1_title'] ?? 'Disiapkan Oleh,' }}</span>
                    <span class="font-bold text-slate-900 block underline text-[13px]">{{ $settings['report_signer_1_name'] ?? 'Koordinator Armada & Logistik' }}</span>
                    <span class="text-slate-500 text-[11px] block mt-0.5">{{ $settings['report_signer_1_role'] ?? 'PT. Erickman Sarana Abadi' }}</span>
                </div>
                <div>
                    <span class="text-slate-500 block mb-14 font-medium">{{ $settings['report_signer_2_title'] ?? 'Disetujui Oleh,' }}</span>
                    <span class="font-bold text-slate-900 block underline text-[13px]">{{ $settings['report_signer_2_name'] ?? 'Direksi / Manajemen' }}</span>
                    <span class="text-slate-500 text-[11px] block mt-0.5">{{ $settings['report_signer_2_role'] ?? 'PT. Erickman Sarana Abadi' }}</span>
                </div>
            </div>
        </div>

    </div>

</body>
</html>