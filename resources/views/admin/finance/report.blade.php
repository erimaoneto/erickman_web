<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan PT Erickman - {{ $periodLabel }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; font-size: 11pt !important; padding: 0 !important; }
            .print-container { border: none !important; box-shadow: none !important; padding: 0 !important; max-width: 100% !important; }
        }
    </style>
</head>
<body class="bg-slate-100 p-4 sm:p-10 font-sans text-slate-800">

    <div class="print-container max-w-4xl mx-auto bg-white p-6 sm:p-12 rounded-2xl shadow-sm border border-slate-200">
        
        <!-- Print Header & Interactive Controls -->
        <div class="no-print space-y-4 pb-6 mb-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <a href="{{ route('admin.finance.index', ['month' => $selectedMonth, 'year' => $selectedYear]) }}" class="text-xs font-semibold text-slate-600 hover:text-navy-900 flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Kembali ke Dashboard Keuangan</span>
                </a>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.finance.signatures') }}" target="_blank" class="px-4 py-2 rounded-xl bg-white border border-slate-300 text-slate-700 hover:text-navy-900 font-bold text-xs shadow-sm flex items-center gap-2 transition">
                        <i class="fa-solid fa-signature text-brand-600"></i>
                        <span>Atur Penanda Tangan</span>
                    </a>
                    <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow flex items-center justify-center gap-2">
                        <i class="fa-solid fa-print"></i>
                        <span>Cetak / Simpan PDF</span>
                    </button>
                </div>
            </div>

            <!-- Filter Periode Bar -->
            <form action="{{ route('admin.finance.report') }}" method="GET" class="p-4 bg-slate-50 rounded-xl border border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-2 font-bold text-slate-700">
                    <i class="fa-solid fa-filter text-brand-600"></i>
                    <span>Filter Periode Laporan:</span>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <select name="month" class="px-3 py-2 rounded-lg border border-slate-300 text-xs bg-white font-medium focus:ring-2 focus:ring-brand-600">
                        <option value="all" {{ $selectedMonth === 'all' || empty($selectedMonth) ? 'selected' : '' }}>-- Semua Bulan (Tahunan) --</option>
                        @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ (string)$selectedMonth === (string)$m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                        @endfor
                    </select>

                    <select name="year" class="px-3 py-2 rounded-lg border border-slate-300 text-xs bg-white font-medium focus:ring-2 focus:ring-brand-600">
                        @for($y = date('Y') + 1; $y >= date('Y') - 3; $y--)
                        <option value="{{ $y }}" {{ (string)$selectedYear === (string)$y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                        @endfor
                    </select>

                    <button type="submit" class="px-4 py-2 rounded-lg bg-navy-900 hover:bg-navy-800 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                        <i class="fa-solid fa-arrows-rotate text-[10px]"></i>
                        <span>Tampilkan</span>
                    </button>
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
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Laporan Keuangan</span>
                <span class="block text-lg font-bold text-slate-900 mt-0.5">
                    {{ $periodLabel }}
                </span>
                <span class="text-[10px] text-slate-400 block mt-1">Dicetak pada: {{ date('d/m/Y H:i') }} WIB</span>
            </div>
        </div>

        <!-- Summary Totals -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 my-8">
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                <span class="text-xs font-semibold text-slate-500 block">Total Pemasukan</span>
                <span class="text-xl font-bold text-emerald-600 block mt-1">Rp {{ number_format($incomeTotal, 0, ',', '.') }}</span>
            </div>
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                <span class="text-xs font-semibold text-slate-500 block">Total Pengeluaran</span>
                <span class="text-xl font-bold text-rose-600 block mt-1">Rp {{ number_format($expenseTotal, 0, ',', '.') }}</span>
            </div>
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                <span class="text-xs font-semibold text-slate-500 block">Laba Bersih Operasional</span>
                <span class="text-xl font-bold {{ $netProfit >= 0 ? 'text-slate-900' : 'text-rose-600' }} block mt-1">
                    Rp {{ number_format($netProfit, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <!-- Details List -->
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-bold text-sm text-slate-900 uppercase tracking-wider">Rincian Transaksi</h3>
            <span class="text-xs text-slate-500">{{ count($transactions) }} Transaksi</span>
        </div>

        @if($transactions->isEmpty())
        <div class="p-8 text-center my-6 rounded-xl bg-slate-50 border border-dashed border-slate-300 text-slate-500">
            <i class="fa-solid fa-file-circle-question text-3xl text-slate-400 mb-2 block"></i>
            <p class="font-semibold text-sm text-slate-700">Tidak ada data transaksi pada {{ $periodLabel }}</p>
            <p class="text-xs text-slate-400 mt-1">Silakan gunakan filter di bagian atas untuk memilih bulan yang memiliki transaksi atau pilih <strong>"-- Semua Bulan (Tahunan) --"</strong>.</p>
        </div>
        @else
        <table class="w-full text-left text-xs border border-slate-200 divide-y divide-slate-200">
            <thead class="bg-slate-100 font-bold text-slate-700">
                <tr>
                    <th class="p-2.5">Tanggal & Kode</th>
                    <th class="p-2.5">Tipe</th>
                    <th class="p-2.5">Kategori & Ref</th>
                    <th class="p-2.5">Keterangan</th>
                    <th class="p-2.5 text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($transactions as $trx)
                <tr>
                    <td class="p-2.5 font-mono">
                        {{ $trx->transaction_date->format('d/m/Y') }}<br>
                        <span class="text-[10px] text-slate-400">{{ $trx->code }}</span>
                    </td>
                    <td class="p-2.5 font-bold {{ $trx->type === 'pemasukan' ? 'text-emerald-700' : 'text-rose-700' }}">
                        {{ ucfirst($trx->type) }}
                    </td>
                    <td class="p-2.5">
                        <strong>{{ $trx->category }}</strong>
                        @if($trx->fleet)
                        <div class="text-[10px] text-slate-500">Unit: {{ $trx->fleet->plate_number }} ({{ $trx->fleet->vehicle_name }})</div>
                        @endif
                    </td>
                    <td class="p-2.5 text-slate-600">{{ $trx->description ?? '-' }}</td>
                    <td class="p-2.5 text-right font-bold {{ $trx->type === 'pemasukan' ? 'text-emerald-700' : 'text-rose-700' }}">
                        {{ $trx->type === 'pemasukan' ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <!-- Signatures -->
        <div class="mt-14 pt-6 border-t border-slate-200">
            <div class="text-right text-xs text-slate-600 mb-8 font-medium">
                {{ $settings['report_city'] ?? 'Jakarta' }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
            </div>
            <div class="grid grid-cols-2 gap-8 text-xs text-center">
                <div>
                    <span class="text-slate-500 block mb-14 font-medium">{{ $settings['report_signer_1_title'] ?? 'Disiapkan Oleh,' }}</span>
                    <span class="font-bold text-slate-900 block underline text-[13px]">{{ $settings['report_signer_1_name'] ?? 'Staff Keuangan & Logistik' }}</span>
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
