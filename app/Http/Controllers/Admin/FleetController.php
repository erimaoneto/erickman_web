<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fleet;
use App\Models\FleetMaintenance;
use App\Models\FleetType;
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
                  ->orWhere('driver_name', 'like', "%{$search}%")
                  ->orWhere('order_number', 'like', "%{$search}%");
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

        $view = $request->get('view', 'grid');

        // Order by nomor urut (order_number) ASC, then ID ASC
        $fleets = $query->withCount('maintenances')
            ->orderByRaw('COALESCE(order_number, 999999) ASC')
            ->orderBy('id', 'asc')
            ->paginate($view === 'table' ? 25 : 12);

        $types = FleetType::orderBy('name')->pluck('name');
        if ($types->isEmpty()) {
            $types = Fleet::select('type')->distinct()->pluck('type');
        }

        return view('admin.fleets.index', compact('fleets', 'types', 'view'));
    }

    public function create()
    {
        $fleetTypes = FleetType::orderBy('name')->get();
        $nextOrder = (Fleet::max('order_number') ?? 0) + 1;

        return view('admin.fleets.form', compact('fleetTypes', 'nextOrder'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_number' => 'nullable|integer|min:1',
            'plate_number' => 'required|string|max:20|unique:fleets,plate_number',
            'vehicle_name' => 'required|string|max:100',
            'type' => 'required|string|max:100',
            'brand' => 'nullable|string|max:50',
            'year' => 'nullable|string|max:10',
            'capacity' => 'nullable|string|max:50',
            'status' => 'required|in:Tersedia,Dalam Perjalanan,Perawatan,Non-Aktif',
            'driver_name' => 'nullable|string|max:100',
            'kir_expiry' => 'nullable|date',
            'stnk_expiry' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'image_path' => 'nullable|string',
            'stnk_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'bpkb_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'kir_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'notes' => 'nullable|string',
        ]);

        if (empty($validated['order_number'])) {
            $validated['order_number'] = (Fleet::max('order_number') ?? 0) + 1;
        }

        // Upload main vehicle photo
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'fleet_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $validated['image_path'] = '/images/' . $filename;
        } elseif (empty($validated['image_path'])) {
            $validated['image_path'] = str_contains($validated['type'], 'CNG') ? '/images/truck-cng-green.jpg' : '/images/truck-box-red.jpg';
        }

        // Upload documents (STNK, BPKB, KIR)
        $cleanPlate = preg_replace('/[^A-Za-z0-9]/', '', $validated['plate_number']);
        $docFolder = public_path('uploads/fleets/documents');
        if (!file_exists($docFolder)) {
            mkdir($docFolder, 0755, true);
        }

        if ($request->hasFile('stnk_file')) {
            $file = $request->file('stnk_file');
            $filename = 'stnk_' . $cleanPlate . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($docFolder, $filename);
            $validated['stnk_file'] = '/uploads/fleets/documents/' . $filename;
        }

        if ($request->hasFile('bpkb_file')) {
            $file = $request->file('bpkb_file');
            $filename = 'bpkb_' . $cleanPlate . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($docFolder, $filename);
            $validated['bpkb_file'] = '/uploads/fleets/documents/' . $filename;
        }

        if ($request->hasFile('kir_file')) {
            $file = $request->file('kir_file');
            $filename = 'kir_' . $cleanPlate . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($docFolder, $filename);
            $validated['kir_file'] = '/uploads/fleets/documents/' . $filename;
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
        $fleetTypes = FleetType::orderBy('name')->get();

        return view('admin.fleets.form', compact('fleet', 'fleetTypes'));
    }

    public function update(Request $request, $id)
    {
        $fleet = Fleet::findOrFail($id);

        $validated = $request->validate([
            'order_number' => 'nullable|integer|min:1',
            'plate_number' => 'required|string|max:20|unique:fleets,plate_number,' . $fleet->id,
            'vehicle_name' => 'required|string|max:100',
            'type' => 'required|string|max:100',
            'brand' => 'nullable|string|max:50',
            'year' => 'nullable|string|max:10',
            'capacity' => 'nullable|string|max:50',
            'status' => 'required|in:Tersedia,Dalam Perjalanan,Perawatan,Non-Aktif',
            'driver_name' => 'nullable|string|max:100',
            'kir_expiry' => 'nullable|date',
            'stnk_expiry' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'stnk_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'bpkb_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'kir_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'notes' => 'nullable|string',
        ]);

        if (empty($validated['order_number'])) {
            $validated['order_number'] = $fleet->order_number ?: ((Fleet::max('order_number') ?? 0) + 1);
        }

        // Upload main vehicle photo
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'fleet_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $validated['image_path'] = '/images/' . $filename;
        }

        // Upload documents (STNK, BPKB, KIR)
        $cleanPlate = preg_replace('/[^A-Za-z0-9]/', '', $validated['plate_number']);
        $docFolder = public_path('uploads/fleets/documents');
        if (!file_exists($docFolder)) {
            mkdir($docFolder, 0755, true);
        }

        if ($request->hasFile('stnk_file')) {
            $file = $request->file('stnk_file');
            $filename = 'stnk_' . $cleanPlate . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($docFolder, $filename);
            $validated['stnk_file'] = '/uploads/fleets/documents/' . $filename;
        }

        if ($request->hasFile('bpkb_file')) {
            $file = $request->file('bpkb_file');
            $filename = 'bpkb_' . $cleanPlate . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($docFolder, $filename);
            $validated['bpkb_file'] = '/uploads/fleets/documents/' . $filename;
        }

        if ($request->hasFile('kir_file')) {
            $file = $request->file('kir_file');
            $filename = 'kir_' . $cleanPlate . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($docFolder, $filename);
            $validated['kir_file'] = '/uploads/fleets/documents/' . $filename;
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

    // Print / PDF Report of Fleet Table
    public function report(Request $request)
    {
        $query = Fleet::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('plate_number', 'like', "%{$search}%")
                  ->orWhere('vehicle_name', 'like', "%{$search}%")
                  ->orWhere('driver_name', 'like', "%{$search}%")
                  ->orWhere('order_number', 'like', "%{$search}%");
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

        $fleets = $query->withCount('maintenances')
            ->orderByRaw('COALESCE(order_number, 999999) ASC')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.fleets.report', compact('fleets'));
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

        $fleet->maintenances()->create($validated);

        return redirect()->route('admin.fleets.show', $fleet->id)->with('success', 'Catatan servis baru berhasil ditambahkan!');
    }

    public function deleteMaintenance($id)
    {
        $maintenance = FleetMaintenance::findOrFail($id);
        $fleetId = $maintenance->fleet_id;
        $maintenance->delete();

        return redirect()->route('admin.fleets.show', $fleetId)->with('success', 'Catatan servis berhasil dihapus!');
    }
}
