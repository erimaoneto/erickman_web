<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GtmModule;
use App\Models\Fleet;

class GtmController extends Controller
{
    public function index(Request $request)
    {
        $query = GtmModule::with('fleet');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tube_code', 'like', "%{$search}%")
                  ->orWhere('tube_brand', 'like', "%{$search}%")
                  ->orWhere('capacity', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('coi_mdr', 'like', "%{$search}%")
                  ->orWhere('manufacture_year', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('fleet_id')) {
            $query->where('fleet_id', $request->fleet_id);
        }

        $gtms = $query->orderByRaw('COALESCE(order_number, 999999) ASC')
            ->orderBy('id', 'asc')
            ->paginate(20);

        $fleets = Fleet::orderBy('vehicle_name')->get();

        $stats = [
            'total' => GtmModule::count(),
            'aktif' => GtmModule::where('status', 'aktif')->count(),
            'standby' => GtmModule::where('status', 'standby')->count(),
            'rekualifikasi' => GtmModule::where('status', 'rekualifikasi')->count(),
            'perbaikan' => GtmModule::where('status', 'perbaikan')->count(),
        ];

        return view('admin.gtm.index', compact('gtms', 'fleets', 'stats'));
    }

    public function create()
    {
        $fleets = Fleet::orderBy('vehicle_name')->get();
        $nextOrder = (GtmModule::max('order_number') ?? 0) + 1;

        return view('admin.gtm.form', compact('fleets', 'nextOrder'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_number' => 'nullable|integer|min:1',
            'tube_code' => 'required|string|max:100|unique:gtm_modules,tube_code',
            'tube_brand' => 'required|string|max:100',
            'capacity' => 'required|string|max:100',
            'manufacture_year' => 'required|string|max:10',
            'fleet_id' => 'nullable|exists:fleets,id',
            'location' => 'nullable|string|max:150',
            'coi_mdr' => 'nullable|string|max:150',
            'hydrotest_expiry' => 'nullable|date',
            'status' => 'required|string|in:aktif,standby,rekualifikasi,perbaikan',
            'notes' => 'nullable|string',
        ]);

        if (empty($validated['order_number'])) {
            $validated['order_number'] = (GtmModule::max('order_number') ?? 0) + 1;
        }

        GtmModule::create($validated);

        return redirect()->route('admin.gtm.index')->with('success', 'Data GTM tabung berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $gtm = GtmModule::findOrFail($id);
        $fleets = Fleet::orderBy('vehicle_name')->get();

        return view('admin.gtm.form', compact('gtm', 'fleets'));
    }

    public function update(Request $request, $id)
    {
        $gtm = GtmModule::findOrFail($id);

        $validated = $request->validate([
            'order_number' => 'nullable|integer|min:1',
            'tube_code' => 'required|string|max:100|unique:gtm_modules,tube_code,' . $gtm->id,
            'tube_brand' => 'required|string|max:100',
            'capacity' => 'required|string|max:100',
            'manufacture_year' => 'required|string|max:10',
            'fleet_id' => 'nullable|exists:fleets,id',
            'location' => 'nullable|string|max:150',
            'coi_mdr' => 'nullable|string|max:150',
            'hydrotest_expiry' => 'nullable|date',
            'status' => 'required|string|in:aktif,standby,rekualifikasi,perbaikan',
            'notes' => 'nullable|string',
        ]);

        $gtm->update($validated);

        return redirect()->route('admin.gtm.index')->with('success', 'Data GTM tabung berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $gtm = GtmModule::findOrFail($id);
        $gtm->delete();

        return redirect()->route('admin.gtm.index')->with('success', 'Data GTM tabung berhasil dihapus!');
    }

    public function report(Request $request)
    {
        $query = GtmModule::with('fleet');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tube_code', 'like', "%{$search}%")
                  ->orWhere('tube_brand', 'like', "%{$search}%")
                  ->orWhere('capacity', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('coi_mdr', 'like', "%{$search}%")
                  ->orWhere('manufacture_year', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('fleet_id')) {
            $query->where('fleet_id', $request->fleet_id);
        }

        $gtms = $query->orderByRaw('COALESCE(order_number, 999999) ASC')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.gtm.report', compact('gtms'));
    }
}
