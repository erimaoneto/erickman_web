<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Fleet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['fleet', 'creator']);

        $selectedYear = $request->get('year', (string) Carbon::now()->year);
        $selectedMonth = $request->get('month', 'all');

        if ($selectedMonth && $selectedMonth !== 'all') {
            $query->whereMonth('transaction_date', (int)$selectedMonth);
        }
        if ($selectedYear && $selectedYear !== 'all') {
            $query->whereYear('transaction_date', (int)$selectedYear);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('fleet_id')) {
            $query->where('fleet_id', $request->fleet_id);
        }
        if ($request->filled('created_by')) {
            $query->where('created_by', $request->created_by);
        }

        // Clone query for sums
        $incomeTotal = (clone $query)->where('type', 'pemasukan')->sum('amount');
        $expenseTotal = (clone $query)->where('type', 'pengeluaran')->sum('amount');
        $netProfit = $incomeTotal - $expenseTotal;

        $transactions = $query->orderBy('transaction_date', 'desc')->latest()->paginate(15);
        $fleets = Fleet::orderBy('vehicle_name')->get();
        $creators = \App\Models\User::orderBy('name')->get();

        $categories = [
            'pemasukan' => ['Distribusi Gas CNG', 'Sewa Truk', 'Penjualan Gas & Bahan Bakar', 'Jasa Kontrak Logistik', 'Lain-lain'],
            'pengeluaran' => ['BBM & Bahan Bakar', 'Uang Jalan Supir', 'Maintenance Armada', 'Gaji & Upah', 'Tol & Parkir', 'Pajak & Legalitas (KIR/STNK)', 'Operasional Kantor', 'Lain-lain'],
        ];

        return view('admin.finance.index', compact(
            'transactions',
            'fleets',
            'creators',
            'incomeTotal',
            'expenseTotal',
            'netProfit',
            'selectedMonth',
            'selectedYear',
            'categories'
        ));
    }

    public function create()
    {
        $fleets = Fleet::orderBy('vehicle_name')->get();
        return view('admin.finance.form', compact('fleets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:pemasukan,pengeluaran',
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:1',
            'transaction_date' => 'required|date',
            'fleet_id' => 'nullable|exists:fleets,id',
            'reference_invoice' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        // Generate unique collision-proof code
        $prefix = 'TRX-' . Carbon::now()->format('Ym') . '-';
        $latestTrx = Transaction::where('code', 'like', $prefix . '%')->orderBy('id', 'desc')->first();
        $nextNumber = 1;
        if ($latestTrx && preg_match('/TRX-\d{6}-(\d+)/', $latestTrx->code, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        }
        $code = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        while (Transaction::where('code', $code)->exists()) {
            $nextNumber++;
            $code = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        }
        $validated['code'] = $code;
        $validated['created_by'] = Auth::id();

        Transaction::create($validated);

        return redirect()->route('admin.finance.index')->with('success', 'Transaksi keuangan berhasil dicatat!');
    }

    public function edit($id)
    {
        $transaction = Transaction::findOrFail($id);
        $fleets = Fleet::orderBy('vehicle_name')->get();
        return view('admin.finance.form', compact('transaction', 'fleets'));
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $validated = $request->validate([
            'type' => 'required|in:pemasukan,pengeluaran',
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:1',
            'transaction_date' => 'required|date',
            'fleet_id' => 'nullable|exists:fleets,id',
            'reference_invoice' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        $transaction->update($validated);

        return redirect()->route('admin.finance.index')->with('success', "Transaksi {$transaction->code} berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $code = $transaction->code;
        $transaction->delete();

        return redirect()->route('admin.finance.index')->with('success', "Transaksi {$code} berhasil dihapus!");
    }

    public function report(Request $request)
    {
        $selectedYear = $request->get('year', (string) Carbon::now()->year);
        $selectedMonth = $request->get('month', 'all');

        $query = Transaction::with(['fleet', 'creator']);

        if ($selectedMonth && $selectedMonth !== 'all') {
            $query->whereMonth('transaction_date', (int)$selectedMonth);
            $monthName = Carbon::create()->month((int)$selectedMonth)->translatedFormat('F');
            $periodLabel = "Periode {$monthName} {$selectedYear}";
        } else {
            $periodLabel = "Periode Tahun {$selectedYear} (Semua Bulan)";
        }

        if ($selectedYear && $selectedYear !== 'all') {
            $query->whereYear('transaction_date', (int)$selectedYear);
        }

        $transactions = $query->orderBy('transaction_date', 'asc')->get();

        $incomeTotal = $transactions->where('type', 'pemasukan')->sum('amount');
        $expenseTotal = $transactions->where('type', 'pengeluaran')->sum('amount');
        $netProfit = $incomeTotal - $expenseTotal;

        $incomeByCategory = $transactions->where('type', 'pemasukan')->groupBy('category')->map->sum('amount');
        $expenseByCategory = $transactions->where('type', 'pengeluaran')->groupBy('category')->map->sum('amount');

        // Backward compatibility variables
        $month = $selectedMonth;
        $year = $selectedYear;

        return view('admin.finance.report', compact(
            'transactions',
            'month',
            'year',
            'selectedMonth',
            'selectedYear',
            'periodLabel',
            'incomeTotal',
            'expenseTotal',
            'netProfit',
            'incomeByCategory',
            'expenseByCategory'
        ));
    }

    public function signatures()
    {
        return view('admin.finance.signatures');
    }

    public function updateSignatures(Request $request)
    {
        $validated = $request->validate([
            'report_city' => 'nullable|string|max:100',
            'report_signer_1_title' => 'required|string|max:100',
            'report_signer_1_name' => 'required|string|max:100',
            'report_signer_1_role' => 'nullable|string|max:100',
            'report_signer_2_title' => 'required|string|max:100',
            'report_signer_2_name' => 'required|string|max:100',
            'report_signer_2_role' => 'nullable|string|max:100',
        ]);

        foreach ($validated as $key => $value) {
            \App\Models\SiteSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value ?? '',
                    'group' => 'finance_report',
                    'type' => 'text',
                    'label' => ucwords(str_replace('_', ' ', $key)),
                ]
            );
        }

        return redirect()->route('admin.finance.signatures')
            ->with('success', 'Pengaturan penanda tangan cetak laporan berhasil diperbarui!');
    }
}
