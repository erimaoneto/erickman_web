@extends('layouts.admin')

@section('title', 'Dashboard Utama')
@section('page_title', 'Ringkasan & Operasional Bisnis')

@section('content')

    <!-- Expiry Alert Banner (KIR / STNK) -->
    @if($urgentFleets->count() > 0)
    <div class="p-5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 shadow-sm space-y-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2.5 font-bold text-sm text-amber-800">
                <i class="fa-solid fa-triangle-exclamation text-amber-600 text-lg"></i>
                <span>Peringatan Masa Berlaku Uji KIR / Pajak STNK ({{ $urgentFleets->count() }} Unit Perlu Perhatian)</span>
            </div>
            <a href="{{ route('admin.fleets.index', ['filter' => 'urgent_kir']) }}" class="text-xs font-bold text-amber-800 hover:underline">
                Lihat Semua Armada &rarr;
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($urgentFleets as $uf)
            <div class="bg-white p-3 rounded-xl border border-amber-200 text-xs flex items-center justify-between shadow-xs">
                <div>
                    <span class="font-mono font-bold text-slate-800 block">{{ $uf->plate_number }}</span>
                    <span class="text-[11px] text-slate-500">{{ $uf->vehicle_name }}</span>
                </div>
                <div class="text-right">
                    @if($uf->kir_expiry && \Carbon\Carbon::now()->diffInDays($uf->kir_expiry, false) <= 30)
                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold {{ \Carbon\Carbon::now()->diffInDays($uf->kir_expiry, false) < 0 ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800' }}">
                            KIR: {{ $uf->kir_expiry->format('d/m/Y') }}
                        </span>
                    @endif
                    @if($uf->stnk_expiry && \Carbon\Carbon::now()->diffInDays($uf->stnk_expiry, false) <= 30)
                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold mt-0.5 {{ \Carbon\Carbon::now()->diffInDays($uf->stnk_expiry, false) < 0 ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800' }}">
                            STNK: {{ $uf->stnk_expiry->format('d/m/Y') }}
                        </span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Key Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Fleet Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Unit Armada</span>
                <div class="text-2xl sm:text-3xl font-black text-navy-900">{{ $totalFleets }} <span class="text-xs font-medium text-slate-500">Unit</span></div>
                <div class="text-[11px] text-slate-500 flex items-center gap-2 pt-1">
                    <span class="text-emerald-600 font-semibold">{{ $availableFleets }} Tersedia</span>
                    <span>&bull;</span>
                    <span class="text-blue-600 font-semibold">{{ $activeFleets }} Bertugas</span>
                    <span>&bull;</span>
                    <span class="text-amber-600 font-semibold">{{ $maintenanceFleets }} Servis</span>
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-truck-moving"></i>
            </div>
        </div>

        <!-- Monthly Income -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Pemasukan Bulan Ini</span>
                <div class="text-2xl sm:text-3xl font-black text-emerald-600">Rp {{ number_format($incomeThisMonth, 0, ',', '.') }}</div>
                <div class="text-[11px] text-slate-500 pt-1">
                    Total All Time: Rp {{ number_format($totalIncome, 0, ',', '.') }}
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-arrow-trend-up"></i>
            </div>
        </div>

        <!-- Monthly Expense -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Pengeluaran Bulan Ini</span>
                <div class="text-2xl sm:text-3xl font-black text-rose-600">Rp {{ number_format($expenseThisMonth, 0, ',', '.') }}</div>
                <div class="text-[11px] text-slate-500 pt-1">
                    BBM, Supir, Tol & Maintenance
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-arrow-trend-down"></i>
            </div>
        </div>

        <!-- Net Profit -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Laba Bersih Bulan Ini</span>
                <div class="text-2xl sm:text-3xl font-black {{ $netProfitThisMonth >= 0 ? 'text-navy-900' : 'text-rose-600' }}">
                    Rp {{ number_format($netProfitThisMonth, 0, ',', '.') }}
                </div>
                <div class="text-[11px] font-semibold {{ $netProfitThisMonth >= 0 ? 'text-emerald-600' : 'text-rose-600' }} pt-1">
                    {{ $netProfitThisMonth >= 0 ? 'Margin Operasional Positif' : 'Perlu Evaluasi Pengeluaran' }}
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-scale-balanced"></i>
            </div>
        </div>
    </div>

    <!-- Interactive Cash Flow Chart -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="font-bold text-base text-navy-900">Tren Arus Kas (Pemasukan vs Pengeluaran 6 Bulan Terakhir)</h3>
                <p class="text-xs text-slate-500">Visualisasi performa pendapatan operasional distribusi gas dan angkutan barang.</p>
            </div>
            <a href="{{ route('admin.finance.index') }}" class="text-xs font-bold text-brand-600 hover:underline">
                Kelola Detail Transaksi &rarr;
            </a>
        </div>
        <div class="h-72 w-full">
            <canvas id="cashflowChart"></canvas>
        </div>
    </div>

    <!-- Split Grid: Recent Inquiries & Recent Transactions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Inquiries -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <h3 class="font-bold text-base text-navy-900">Pesan Masuk Website</h3>
                    @if($unreadInquiries > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500 text-white">{{ $unreadInquiries }} Baru</span>
                    @endif
                </div>
                <a href="{{ route('admin.email.inbox') }}" class="text-xs font-bold text-brand-600 hover:underline">Lihat Semua</a>
            </div>

            <div class="space-y-3">
                @forelse($recentInquiries as $inq)
                <div class="p-3.5 rounded-xl border border-slate-100 hover:border-slate-200 bg-slate-50/60 flex items-start justify-between gap-3">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-xs text-navy-900">{{ $inq->name }}</span>
                            @if(!$inq->is_read)
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            @endif
                            @if($inq->company)
                            <span class="text-[11px] text-slate-500">({{ $inq->company }})</span>
                            @endif
                        </div>
                        <h4 class="text-xs font-semibold text-slate-700">{{ $inq->subject }}</h4>
                        <p class="text-[11px] text-slate-500 line-clamp-1">{{ $inq->message }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <span class="text-[10px] text-slate-400 block">{{ $inq->created_at->diffForHumans() }}</span>
                        <a href="{{ route('admin.email.show', $inq->id) }}" class="mt-1 inline-block text-[11px] font-bold text-brand-600 hover:underline">
                            Buka & Balas
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-xs text-slate-400">Belum ada pesan masuk.</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-base text-navy-900">Transaksi Keuangan Terbaru</h3>
                <a href="{{ route('admin.finance.index') }}" class="text-xs font-bold text-brand-600 hover:underline">Lihat Semua</a>
            </div>

            <div class="space-y-3">
                @forelse($recentTransactions as $trx)
                <div class="p-3.5 rounded-xl border border-slate-100 hover:border-slate-200 bg-slate-50/60 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold {{ $trx->type === 'pemasukan' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                            <i class="fa-solid {{ $trx->type === 'pemasukan' ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
                        </div>
                        <div>
                            <span class="font-semibold text-xs text-navy-900 block">{{ $trx->category }}</span>
                            <span class="text-[10px] text-slate-500">{{ $trx->code }} &bull; {{ $trx->transaction_date->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold block {{ $trx->type === 'pemasukan' ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $trx->type === 'pemasukan' ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                        </span>
                        @if($trx->fleet)
                        <span class="text-[10px] text-slate-400 font-mono">{{ $trx->fleet->plate_number }}</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-xs text-slate-400">Belum ada catatan transaksi.</div>
                @endforelse
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('cashflowChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [
                    {
                        label: 'Pemasukan (IDR)',
                        data: {!! json_encode($incomeData) !!},
                        backgroundColor: '#10b981',
                        borderRadius: 6,
                    },
                    {
                        label: 'Pengeluaran (IDR)',
                        data: {!! json_encode($expenseData) !!},
                        backgroundColor: '#f43f5e',
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: { family: '"Plus Jakarta Sans"', size: 12 }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + (value / 1000000) + ' Jt';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
