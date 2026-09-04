@extends('layouts.admin')

@section('title', 'Dashboard Keuangan')
@section('page_title', 'Dashboard Keuangan: Arus Kas & Pencatatan Transaksi')

@section('content')

    <div class="space-y-6">
        <!-- Header & Action -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-navy-900">Arus Kas & Pembukuan Operasional</h2>
                <p class="text-xs text-slate-500">Pencatatan pendapatan distribusi/sewa armada dan pengeluaran operasional (BBM, supir, servis).</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.finance.report', ['month' => $selectedMonth, 'year' => $selectedYear]) }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-navy-900 font-bold text-xs uppercase tracking-wider transition flex items-center gap-2">
                    <i class="fa-solid fa-print"></i>
                    <span>Cetak Laporan</span>
                </a>
                <a href="{{ route('admin.finance.create') }}" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-brand-600/20 transition flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Catat Transaksi</span>
                </a>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
            <form action="{{ route('admin.finance.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                <div>
                    <select name="month" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-600">
                        @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                        @endfor
                    </select>
                </div>

                <div>
                    <select name="year" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-600">
                        @for($y = date('Y') + 1; $y >= date('Y') - 3; $y--)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <select name="type" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-600">
                        <option value="">-- Semua Jenis --</option>
                        <option value="pemasukan" {{ request('type') == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                        <option value="pengeluaran" {{ request('type') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                    </select>
                </div>

                <div>
                    <select name="fleet_id" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-600">
                        <option value="">-- Terkait Armada --</option>
                        @foreach($fleets as $f)
                        <option value="{{ $f->id }}" {{ request('fleet_id') == $f->id ? 'selected' : '' }}>{{ $f->plate_number }} ({{ $f->vehicle_name }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select name="created_by" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-600">
                        <option value="">-- Diinput Oleh --</option>
                        @foreach($creators as $cr)
                        <option value="{{ $cr->id }}" {{ request('created_by') == $cr->id ? 'selected' : '' }}>{{ $cr->name }} ({{ ucfirst($cr->role) }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 py-2 rounded-xl bg-navy-900 text-white font-bold text-xs hover:bg-navy-800 transition">
                        Filter
                    </button>
                    <a href="{{ route('admin.finance.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 text-slate-600 text-xs font-semibold hover:bg-slate-200 transition">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Summary Cards for Filtered Period -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Pemasukan Periode Ini</span>
                    <span class="text-2xl font-black text-emerald-600 mt-1 block">Rp {{ number_format($incomeTotal, 0, ',', '.') }}</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-arrow-down"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Pengeluaran Periode Ini</span>
                    <span class="text-2xl font-black text-rose-600 mt-1 block">Rp {{ number_format($expenseTotal, 0, ',', '.') }}</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-arrow-up"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Laba Bersih Periode Ini</span>
                    <span class="text-2xl font-black {{ $netProfit >= 0 ? 'text-navy-900' : 'text-rose-600' }} mt-1 block">
                        Rp {{ number_format($netProfit, 0, ',', '.') }}
                    </span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-scale-balanced"></i>
                </div>
            </div>
        </div>

        <!-- Transaction Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-base text-navy-900">Buku Transaksi Keuangan</h3>
                <span class="text-xs text-slate-500">{{ $transactions->total() }} Data Transaksi</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[11px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">Kode & Tanggal</th>
                            <th class="py-3 px-4">Tipe</th>
                            <th class="py-3 px-4">Kategori & Armada</th>
                            <th class="py-3 px-4">Keterangan / Ref</th>
                            <th class="py-3 px-4">Diinput Oleh</th>
                            <th class="py-3 px-4 text-right">Jumlah (IDR)</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($transactions as $trx)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 font-semibold text-slate-800">
                                <span class="font-mono text-navy-900 block font-bold">{{ $trx->code }}</span>
                                <span class="text-[11px] text-slate-400">{{ $trx->transaction_date->format('d/m/Y') }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $trx->type === 'pemasukan' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ ucfirst($trx->type) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-800 block">{{ $trx->category }}</span>
                                @if($trx->fleet)
                                <span class="text-[11px] text-brand-600 font-mono font-semibold">Unit: {{ $trx->fleet->plate_number }} ({{ $trx->fleet->vehicle_name }})</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-600">
                                @if($trx->reference_invoice)
                                <span class="font-mono text-[10px] font-bold text-slate-700 bg-slate-100 px-1.5 py-0.5 rounded mr-1">Ref: {{ $trx->reference_invoice }}</span>
                                @endif
                                <span>{{ $trx->description ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-semibold block text-slate-800">{{ $trx->creator->name ?? 'Administrator' }}</span>
                                <span class="inline-block px-1.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider {{ ($trx->creator->role ?? 'superadmin') === 'keuangan' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ ($trx->creator->role ?? 'superadmin') === 'keuangan' ? 'Keuangan' : 'Admin' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right font-black {{ $trx->type === 'pemasukan' ? 'text-emerald-600' : 'text-rose-600' }} text-sm">
                                {{ $trx->type === 'pemasukan' ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.finance.edit', $trx->id) }}" class="p-1.5 text-slate-400 hover:text-brand-600 transition" title="Edit Transaksi">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('admin.finance.destroy', $trx->id) }}" method="POST" onsubmit="return confirm('Hapus transaksi {{ $trx->code }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700" title="Hapus Transaksi">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">Tidak ada transaksi ditemukan pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>

@endsection
