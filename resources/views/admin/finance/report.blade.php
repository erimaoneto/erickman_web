<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan PT Erickman - Periode {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }} {{ $year }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; font-size: 11pt !important; }
        }
    </style>
</head>
<body class="bg-slate-100 p-4 sm:p-10 font-sans text-slate-800">

    <div class="max-w-4xl mx-auto bg-white p-8 sm:p-12 rounded-2xl shadow-sm border border-slate-200">
        <!-- Print Header & Controls -->
        <div class="no-print flex items-center justify-between pb-6 mb-6 border-b border-slate-200">
            <a href="{{ route('admin.finance.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900">
                &larr; Kembali ke Dashboard Keuangan
            </a>
            <button onclick="window.print()" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow">
                Cetak / Simpan PDF
            </button>
        </div>

        <!-- Corporate Letterhead -->
        <div class="flex items-center justify-between pb-6 border-b-2 border-slate-900">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">PT. ERICKMAN SARANA ABADI</h1>
                <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold">Transportasi Gas Alam & Logistik Khusus</p>
                <p class="text-[11px] text-slate-500 mt-1">
                    18 Office Park Building Lt. 12 Unit A & H, Jl. TB Simatupang No.18, Jakarta Selatan 12520<br>
                    NIB: 2211210015706 &bull; Email: info@erickman.co.id &bull; Telp: +62 21 2278 1818
                </p>
            </div>
            <div class="text-right">
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Laporan Keuangan</span>
                <span class="block text-lg font-bold text-slate-800">
                    {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }} {{ $year }}
                </span>
                <span class="text-[10px] text-slate-400">Dicetak pada: {{ date('d/m/Y H:i') }} WIB</span>
            </div>
        </div>

        <!-- Summary Totals -->
        <div class="grid grid-cols-3 gap-4 my-8">
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
        <h3 class="font-bold text-sm text-slate-900 mb-3 uppercase tracking-wider">Rincian Transaksi</h3>
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
                        <div class="text-[10px] text-slate-500">Unit: {{ $trx->fleet->plate_number }}</div>
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

        <!-- Signatures -->
        <div class="grid grid-cols-2 gap-8 mt-16 pt-8 border-t border-slate-200 text-xs text-center">
            <div>
                <span class="text-slate-400 block mb-12">Disiapkan Oleh,</span>
                <span class="font-bold text-slate-800 block underline">Staff Keuangan & Logistik</span>
                <span class="text-slate-500">PT Erickman</span>
            </div>
            <div>
                <span class="text-slate-400 block mb-12">Disetujui Oleh,</span>
                <span class="font-bold text-slate-800 block underline">Direksi / Manajemen</span>
                <span class="text-slate-500">PT Erickman</span>
            </div>
        </div>
    </div>

</body>
</html>
