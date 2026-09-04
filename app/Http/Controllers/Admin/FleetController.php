<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fleet;
use App\Models\FleetMaintenance;
use Carbon\Carbon;

class FleetController extends Controller
{
    public function index(Request $request)
    {
        $query = Fleet::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('plate_number', 'like', "%{$search}%")
                  ->orWhere('vehicle_name', 'like', "%{$search}%")
                  ->orWhere('driver_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filter == 'urgent_kir') {
            $query->whereDate('kir_expiry', '<=', Carbon::now()->addDays(30));
        } elseif ($request->filter == 'urgent_stnk') {
            $query->whereDate('stnk_expiry', '<=', Carbon::now()->addDays(30));
        }

        $fleets = $query->withCount('maintenances')->latest()->paginate(10);
        $types = Fleet::select('type')->distinct()->pluck('type');

        return view('admin.fleets.index', compact('fleets', 'types'));
    }

    public function create()
    {
        return view('admin.fleets.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plate_number' => 'required|string|max:20|unique:fleets,plate_number',
            'vehicle_name' => 'required|string|max:100',
            'type' => 'required|string|max:50',
            'brand' => 'nullable|string|max:50',
            'year' => 'nullable|string|max:10',
            'capacity' => 'nullable|string|max:50',
            'status' => 'required|in:Tersedia,Dalam Perjalanan,Perawatan,Non-Aktif',
            'driver_name' => 'nullable|string|max:100',
            'kir_expiry' => 'nullable|date',
            'stnk_expiry' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'image_path' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'fleet_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $validated['image_path'] = '/images/' . $filename;
        } elseif (empty($validated['image_path'])) {
            $validated['image_path'] = str_contains($validated['type'], 'CNG') ? '/images/truck-cng-green.jpg' : '/images/truck-box-red.jpg';
        }

        Fleet::create($validated);

        return redirect()->route('admin.fleets.index')->with('success', 'Data armada baru berhasil didaftarkan!');
    }

    public function show($id)
    {
        $fleet = Fleet::with(['maintenances' => function ($q) {
            $q->latest('service_date');
        }])->findOrFail($id);

        $totalMaintenanceCost = $fleet->maintenances->sum('cost');

        return view('admin.fleets.show', compact('fleet', 'totalMaintenanceCost'));
    }

    public function edit($id)
    {
        $fleet = Fleet::findOrFail($id);
        return view('admin.fleets.form', compact('fleet'));
    }

    public function update(Request $request, $id)
    {
        $fleet = Fleet::findOrFail($id);

        $validated = $request->validate([
            'plate_number' => 'required|string|max:20|unique:fleets,plate_number,' . $fleet->id,
            'vehicle_name' => 'required|string|max:100',
            'type' => 'required|string|max:50',
            'brand' => 'nullable|string|max:50',
            'year' => 'nullable|string|max:10',
            'capacity' => 'nullable|string|max:50',
            'status' => 'required|in:Tersedia,Dalam Perjalanan,Perawatan,Non-Aktif',
            'driver_name' => 'nullable|string|max:100',
            'kir_expiry' => 'nullable|date',
            'stnk_expiry' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'fleet_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $validated['image_path'] = '/images/' . $filename;
        }

        $fleet->update($validated);

        return redirect()->route('admin.fleets.show', $fleet->id)->with('success', 'Data armada berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $fleet = Fleet::findOrFail($id);
        $fleet->delete();

        return redirect()->route('admin.fleets.index')->with('success', 'Data armada berhasil dihapus!');
    }

    public function storeMaintenance(Request $request, $fleetId)
    {
        $fleet = Fleet::findOrFail($fleetId);

        $validated = $request->validate([
            'service_date' => 'required|date',
            'service_type' => 'required|string|max:150',
            'cost' => 'required|numeric|min:0',
            'workshop' => 'nullable|string|max:150',
            'odometer_km' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $validated['fleet_id'] = $fleet->id;

        FleetMaintenance::create($validated);

        return redirect()->route('admin.fleets.show', $fleet->id)->with('success', 'Catatan servis/pemeliharaan berhasil ditambahkan!');
    }

    public function deleteMaintenance($id)
    {
        $maintenance = FleetMaintenance::findOrFail($id);
        $fleetId = $maintenance->fleet_id;
        $maintenance->delete();

        return redirect()->route('admin.fleets.show', $fleetId)->with('success', 'Catatan servis berhasil dihapus!');
    }
}
