<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FleetType;
use App\Models\Fleet;

class FleetTypeController extends Controller
{
    public function index()
    {
        $types = FleetType::withCount('fleets')->orderBy('name')->get();
        return view('admin.fleets.types', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:fleet_types,name',
            'description' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Nama tipe armada wajib diisi.',
            'name.unique' => 'Tipe armada ini sudah terdaftar.',
        ]);

        FleetType::create($validated);

        return redirect()->route('admin.fleets.types.index')->with('success', 'Tipe armada baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $type = FleetType::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:fleet_types,name,' . $type->id,
            'description' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Nama tipe armada wajib diisi.',
            'name.unique' => 'Tipe armada ini sudah terdaftar.',
        ]);

        $oldName = $type->name;
        $type->update($validated);

        // If the name changed, update any existing fleets that use this type name
        if ($oldName !== $validated['name']) {
            Fleet::where('type', $oldName)->update(['type' => $validated['name']]);
        }

        return redirect()->route('admin.fleets.types.index')->with('success', 'Tipe armada berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $type = FleetType::withCount('fleets')->findOrFail($id);

        if ($type->fleets_count > 0) {
            return redirect()->route('admin.fleets.types.index')->with('warning', "Tipe armada '{$type->name}' tidak dapat dihapus karena masih digunakan oleh {$type->fleets_count} unit armada.");
        }

        $type->delete();

        return redirect()->route('admin.fleets.types.index')->with('success', 'Tipe armada berhasil dihapus!');
    }
}
