<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fleet;
use App\Models\Transaction;
use App\Models\Inquiry;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Fleet Stats
        $totalFleets = Fleet::count();
        $activeFleets = Fleet::where('status', 'Dalam Perjalanan')->count();
        $availableFleets = Fleet::where('status', 'Tersedia')->count();
        $maintenanceFleets = Fleet::where('status', 'Perawatan')->count();

        // 2. Urgent Expiry Warnings (KIR / STNK <= 30 hari atau sudah expired)
        $urgentFleets = Fleet::where(function ($q) {
            $q->whereDate('kir_expiry', '<=', Carbon::now()->addDays(30))
              ->orWhereDate('stnk_expiry', '<=', Carbon::now()->addDays(30));
        })->get();

        // 3. Financial Stats (This Month)
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $incomeThisMonth = Transaction::where('type', 'pemasukan')
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->sum('amount');

        $expenseThisMonth = Transaction::where('type', 'pengeluaran')
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->sum('amount');

        $netProfitThisMonth = $incomeThisMonth - $expenseThisMonth;

        // All Time Total
        $totalIncome = Transaction::where('type', 'pemasukan')->sum('amount');
        $totalExpense = Transaction::where('type', 'pengeluaran')->sum('amount');

        // 4. Inquiries
        $unreadInquiries = Inquiry::where('is_read', false)->count();
        $recentInquiries = Inquiry::latest()->take(5)->get();

        // 5. Recent Transactions
        $recentTransactions = Transaction::with('fleet')->latest('transaction_date')->take(6)->get();

        // 6. 6 Months Financial Chart Data
        $chartLabels = [];
        $incomeData = [];
        $expenseData = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $chartLabels[] = $month->translatedFormat('M Y');

            $mIncome = Transaction::where('type', 'pemasukan')
                ->whereMonth('transaction_date', $month->month)
                ->whereYear('transaction_date', $month->year)
                ->sum('amount');

            $mExpense = Transaction::where('type', 'pengeluaran')
                ->whereMonth('transaction_date', $month->month)
                ->whereYear('transaction_date', $month->year)
                ->sum('amount');

            $incomeData[] = (float) $mIncome;
            $expenseData[] = (float) $mExpense;
        }

        return view('admin.dashboard', compact(
            'totalFleets',
            'activeFleets',
            'availableFleets',
            'maintenanceFleets',
            'urgentFleets',
            'incomeThisMonth',
            'expenseThisMonth',
            'netProfitThisMonth',
            'totalIncome',
            'totalExpense',
            'unreadInquiries',
            'recentInquiries',
            'recentTransactions',
            'chartLabels',
            'incomeData',
            'expenseData'
        ));
    }
}
