<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;
use App\Models\Banner;
use App\Models\Service;
use App\Models\NavMenu;
use App\Models\KeyMetric;
use App\Models\AboutPillar;
use App\Models\HseItem;
use App\Models\Partner;
use Illuminate\Support\Str;

class ContentController extends Controller
{
    // --- SETTINGS (CMS) ---
    public function settings()
    {
        $settings = SiteSetting::pluck('value', 'key')->all();
        return view('admin.content.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->except(['_token', '_method', 'about_image_main_file', 'about_image_secondary_file', 'rekanan_banner_image_file']);

        // Handle Image Uploads
        if ($request->hasFile('about_image_main_file')) {
            $file = $request->file('about_image_main_file');
            $filename = 'about_main_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $data['about_image_main'] = '/images/' . $filename;
        }

        if ($request->hasFile('about_image_secondary_file')) {
            $file = $request->file('about_image_secondary_file');
            $filename = 'about_secondary_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $data['about_image_secondary'] = '/images/' . $filename;
        }

        if ($request->hasFile('rekanan_banner_image_file')) {
            $file = $request->file('rekanan_banner_image_file');
            $filename = 'rekanan_banner_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $data['rekanan_banner_image'] = '/images/' . $filename;
        }

        // Toggles (checkboxes send value only if checked)
        $toggles = ['show_rfq_form', 'show_stats_section', 'show_hse_section', 'show_rekanan_section', 'show_armada_section'];
        foreach ($toggles as $toggle) {
            $data[$toggle] = $request->has($toggle) ? '1' : '0';
        }

        foreach ($data as $key => $value) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'Pengaturan profil, modul section, dan gambar website berhasil diperbarui!');
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

    // --- NAV MENUS ---
    public function menus()
    {
        $menus = NavMenu::orderBy('order', 'asc')->get();
        return view('admin.content.menus', compact('menus'));
    }

    public function storeMenu(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'url' => 'required|string|max:200',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        NavMenu::create($validated);
        return redirect()->route('admin.content.menus')->with('success', 'Menu navigasi berhasil ditambahkan!');
    }

    public function updateMenu(Request $request, $id)
    {
        $menu = NavMenu::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'url' => 'required|string|max:200',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        $menu->update($validated);
        return redirect()->route('admin.content.menus')->with('success', 'Menu navigasi berhasil diperbarui!');
    }

    public function deleteMenu($id)
    {
        $menu = NavMenu::findOrFail($id);
        $menu->delete();
        return redirect()->route('admin.content.menus')->with('success', 'Menu navigasi berhasil dihapus!');
    }

    // --- KEY METRICS ---
    public function metrics()
    {
        $metrics = KeyMetric::orderBy('order', 'asc')->get();
        return view('admin.content.metrics', compact('metrics'));
    }

    public function storeMetric(Request $request)
    {
        $validated = $request->validate([
            'number_value' => 'required|string|max:50',
            'label' => 'required|string|max:150',
            'color_theme' => 'nullable|string|max:50',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['color_theme'] = $validated['color_theme'] ?? 'brand';
        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        KeyMetric::create($validated);
        return redirect()->route('admin.content.metrics')->with('success', 'Statistik metrik berhasil ditambahkan!');
    }

    public function updateMetric(Request $request, $id)
    {
        $metric = KeyMetric::findOrFail($id);

        $validated = $request->validate([
            'number_value' => 'required|string|max:50',
            'label' => 'required|string|max:150',
            'color_theme' => 'nullable|string|max:50',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['color_theme'] = $validated['color_theme'] ?? 'brand';
        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        $metric->update($validated);
        return redirect()->route('admin.content.metrics')->with('success', 'Statistik metrik berhasil diperbarui!');
    }

    public function deleteMetric($id)
    {
        $metric = KeyMetric::findOrFail($id);
        $metric->delete();
        return redirect()->route('admin.content.metrics')->with('success', 'Statistik metrik berhasil dihapus!');
    }

    // --- ABOUT PILLARS ---
    public function pillars()
    {
        $pillars = AboutPillar::orderBy('order', 'asc')->get();
        return view('admin.content.pillars', compact('pillars'));
    }

    public function storePillar(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'required|string|max:500',
            'icon' => 'nullable|string|max:100',
            'color_theme' => 'nullable|string|max:50',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['icon'] = $validated['icon'] ?: 'fa-solid fa-fire-flame-simple';
        $validated['color_theme'] = $validated['color_theme'] ?: 'orange';
        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        AboutPillar::create($validated);
        return redirect()->route('admin.content.pillars')->with('success', 'Pilar bisnis berhasil ditambahkan!');
    }

    public function updatePillar(Request $request, $id)
    {
        $pillar = AboutPillar::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'required|string|max:500',
            'icon' => 'nullable|string|max:100',
            'color_theme' => 'nullable|string|max:50',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['icon'] = $validated['icon'] ?: 'fa-solid fa-fire-flame-simple';
        $validated['color_theme'] = $validated['color_theme'] ?: 'orange';
        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        $pillar->update($validated);
        return redirect()->route('admin.content.pillars')->with('success', 'Pilar bisnis berhasil diperbarui!');
    }

    public function deletePillar($id)
    {
        $pillar = AboutPillar::findOrFail($id);
        $pillar->delete();
        return redirect()->route('admin.content.pillars')->with('success', 'Pilar bisnis berhasil dihapus!');
    }

    // --- HSE / K3 ---
    public function hse()
    {
        $hseItems = HseItem::orderBy('order', 'asc')->get();
        return view('admin.content.hse', compact('hseItems'));
    }

    public function storeHse(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'required|string|max:500',
            'icon' => 'nullable|string|max:100',
            'color_theme' => 'nullable|string|max:50',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['icon'] = $validated['icon'] ?: 'fa-solid fa-shield-halved';
        $validated['color_theme'] = $validated['color_theme'] ?: 'brand';
        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        HseItem::create($validated);
        return redirect()->route('admin.content.hse')->with('success', 'Standar keselamatan HSE berhasil ditambahkan!');
    }

    public function updateHse(Request $request, $id)
    {
        $hse = HseItem::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'required|string|max:500',
            'icon' => 'nullable|string|max:100',
            'color_theme' => 'nullable|string|max:50',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['icon'] = $validated['icon'] ?: 'fa-solid fa-shield-halved';
        $validated['color_theme'] = $validated['color_theme'] ?: 'brand';
        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        $hse->update($validated);
        return redirect()->route('admin.content.hse')->with('success', 'Standar keselamatan HSE berhasil diperbarui!');
    }

    public function deleteHse($id)
    {
        $hse = HseItem::findOrFail($id);
        $hse->delete();
        return redirect()->route('admin.content.hse')->with('success', 'Standar keselamatan HSE berhasil dihapus!');
    }

    // --- PARTNERS / REKANAN ---
    public function partners()
    {
        $partners = Partner::orderBy('order', 'asc')->get();
        return view('admin.content.partners', compact('partners'));
    }

    public function storePartner(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'subtitle' => 'nullable|string|max:150',
            'icon' => 'nullable|string|max:100',
            'color_theme' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'partner_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $validated['logo_path'] = '/images/' . $filename;
        }

        $validated['icon'] = $validated['icon'] ?: 'fa-solid fa-building';
        $validated['color_theme'] = $validated['color_theme'] ?: 'blue';
        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        Partner::create($validated);
        return redirect()->route('admin.content.partners')->with('success', 'Mitra rekanan berhasil ditambahkan!');
    }

    public function updatePartner(Request $request, $id)
    {
        $partner = Partner::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'subtitle' => 'nullable|string|max:150',
            'icon' => 'nullable|string|max:100',
            'color_theme' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'partner_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $validated['logo_path'] = '/images/' . $filename;
        }

        $validated['icon'] = $validated['icon'] ?: 'fa-solid fa-building';
        $validated['color_theme'] = $validated['color_theme'] ?: 'blue';
        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        $partner->update($validated);
        return redirect()->route('admin.content.partners')->with('success', 'Mitra rekanan berhasil diperbarui!');
    }

    public function deletePartner($id)
    {
        $partner = Partner::findOrFail($id);
        $partner->delete();
        return redirect()->route('admin.content.partners')->with('success', 'Mitra rekanan berhasil dihapus!');
    }
}
