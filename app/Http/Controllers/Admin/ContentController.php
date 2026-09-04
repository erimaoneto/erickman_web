<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;
use App\Models\Banner;
use App\Models\Service;
use Illuminate\Support\Str;

class ContentController extends Controller
{
    // --- SETTINGS (CMS) ---
    public function settings()
    {
        $settings = SiteSetting::all()->groupBy('group');
        return view('admin.content.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        foreach ($data as $key => $value) {
            SiteSetting::where('key', $key)->update(['value' => $value]);
        }

        return redirect()->back()->with('success', 'Pengaturan profil dan identitas website berhasil diperbarui!');
    }

    // --- BANNERS ---
    public function banners()
    {
        $banners = Banner::orderBy('order', 'asc')->get();
        return view('admin.content.banners', compact('banners'));
    }

    public function storeBanner(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'tagline' => 'nullable|string|max:200',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'image_path' => 'nullable|string',
            'button_text' => 'nullable|string|max:50',
            'button_url' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'banner_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $validated['image_path'] = '/images/' . $filename;
        } elseif (empty($validated['image_path'])) {
            $validated['image_path'] = '/images/truck-cng-green.jpg';
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        Banner::create($validated);

        return redirect()->route('admin.content.banners')->with('success', 'Banner slider berhasil ditambahkan!');
    }

    public function updateBanner(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'tagline' => 'nullable|string|max:200',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'button_text' => 'nullable|string|max:50',
            'button_url' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'banner_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $validated['image_path'] = '/images/' . $filename;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        $banner->update($validated);

        return redirect()->route('admin.content.banners')->with('success', 'Banner slider berhasil diperbarui!');
    }

    public function deleteBanner($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();

        return redirect()->route('admin.content.banners')->with('success', 'Banner berhasil dihapus!');
    }

    // --- SERVICES ---
    public function services()
    {
        $services = Service::orderBy('order', 'asc')->get();
        return view('admin.content.services', compact('services'));
    }

    public function createService()
    {
        return view('admin.content.service_form');
    }

    public function storeService(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'kbli_code' => 'nullable|string|max:100',
            'icon' => 'nullable|string|max:50',
            'short_description' => 'required|string|max:500',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'image_path' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'service_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $validated['image_path'] = '/images/' . $filename;
        } elseif (empty($validated['image_path'])) {
            $validated['image_path'] = '/images/truck-cng-green.jpg';
        }

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        Service::create($validated);

        return redirect()->route('admin.content.services')->with('success', 'Layanan berhasil ditambahkan!');
    }

    public function editService($id)
    {
        $service = Service::findOrFail($id);
        return view('admin.content.service_form', compact('service'));
    }

    public function updateService(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'kbli_code' => 'nullable|string|max:100',
            'icon' => 'nullable|string|max:50',
            'short_description' => 'required|string|max:500',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'service_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $validated['image_path'] = '/images/' . $filename;
        }

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        $service->update($validated);

        return redirect()->route('admin.content.services')->with('success', 'Layanan berhasil diperbarui!');
    }

    public function deleteService($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect()->route('admin.content.services')->with('success', 'Layanan berhasil dihapus!');
    }
}
