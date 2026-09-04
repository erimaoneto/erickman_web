<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Service;
use App\Models\Fleet;
use App\Models\SiteSetting;
use App\Models\Inquiry;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::where('is_active', true)->orderBy('order', 'asc')->get();
        $services = Service::where('is_active', true)->orderBy('order', 'asc')->get();
        $fleets = Fleet::where('status', '!=', 'Non-Aktif')->take(6)->get();
        
        $settings = SiteSetting::pluck('value', 'key')->all();

        return view('frontend.index', compact('banners', 'services', 'fleets', 'settings'));
    }

    public function serviceDetail($slug)
    {
        $service = Service::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $allServices = Service::where('is_active', true)->orderBy('order', 'asc')->get();
        $settings = SiteSetting::pluck('value', 'key')->all();

        return view('frontend.service_detail', compact('service', 'allServices', 'settings'));
    }

    public function submitInquiry(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'company' => 'nullable|string|max:150',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:50',
            'service_interest' => 'nullable|string|max:150',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:2000',
        ]);

        Inquiry::create($validated);

        return redirect()->back()->with('success_inquiry', 'Terima kasih! Pesan dan permintaan penawaran Anda telah berhasil dikirim. Tim PT Erickman akan segera menghubungi Anda.');
    }
}
