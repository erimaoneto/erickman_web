@extends('layouts.app')

@section('title', $service->title . ' - ' . ($settings['company_name'] ?? 'PT Erickman'))

@section('content')
    <!-- Header Banner -->
    <div class="relative bg-navy-950 py-20 text-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-3xl space-y-4">
                @if($service->kbli_code)
                <span class="inline-block px-3 py-1 rounded bg-brand-500/20 text-brand-300 font-mono text-xs font-bold border border-brand-500/30">
                    {{ $service->kbli_code }}
                </span>
                @endif
                <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight">{{ $service->title }}</h1>
                <p class="text-base sm:text-lg text-slate-300">{{ $service->short_description }}</p>
            </div>
        </div>
        <div class="absolute right-0 top-0 bottom-0 w-1/3 opacity-20 hidden lg:block">
            <img src="{{ asset($service->image_path ?? '/images/truck-cng-green.jpg') }}" alt="{{ $service->title }}" class="w-full h-full object-cover">
        </div>
    </div>

    <!-- Main Detail Section -->
    <div class="py-16 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <!-- Content Area -->
                <div class="lg:col-span-8 space-y-8 bg-white p-8 sm:p-10 rounded-3xl border border-slate-200/80 shadow-sm">
                    <div class="rounded-2xl overflow-hidden shadow-md">
                        <img src="{{ asset($service->image_path ?? '/images/truck-cng-green.jpg') }}" alt="{{ $service->title }}" class="w-full h-80 sm:h-96 object-cover">
                    </div>

                    <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed space-y-4">
                        <h3 class="text-2xl font-bold text-navy-900">Deskripsi & Ruang Lingkup Layanan</h3>
                        <p class="text-base">
                            {{ $service->description ?? $service->short_description }}
                        </p>
                        
                        <h4 class="text-lg font-bold text-navy-900 pt-4">Standar Pelaksanaan & Jaminan Layanan:</h4>
                        <ul class="space-y-2 text-sm text-slate-600 list-disc pl-5">
                            <li>Armada beroperasi dengan izin resmi Kementerian ESDM & Perhubungan RI.</li>
                            <li>Jadwal keberangkatan terkoordinasi dengan pengawasan dispatch terpusat.</li>
                            <li>Peralatan pelindung diri (APD) lengkap dan sertifikasi K3 bagi setiap personel lapangan.</li>
                            <li>Fleksibilitas skema kontrak: spot order, sewa berkala, atau kontrak suplai jangka panjang.</li>
                        </ul>
                    </div>

                    <!-- Call To Action Button -->
                    <div class="p-6 rounded-2xl bg-emerald-50 border border-emerald-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div>
                            <h4 class="font-bold text-base text-emerald-950">Tertarik dengan Layanan Ini?</h4>
                            <p class="text-xs text-emerald-800">Dapatkan penawaran harga terbaik dan konsultasi teknis gratis dengan tim kami.</p>
                        </div>
                        <a href="{{ route('home') }}#kontak" class="px-6 py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-brand-600/30 transition flex-shrink-0">
                            Minta Penawaran
                        </a>
                    </div>
                </div>

                <!-- Sidebar Other Services -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                        <h3 class="font-bold text-base text-navy-900 border-b border-slate-100 pb-3">Layanan Lainnya</h3>
                        <div class="space-y-2">
                            @foreach($allServices as $s)
                            <a href="{{ route('service.detail', $s->slug) }}" class="flex items-center justify-between p-3 rounded-xl transition text-sm font-semibold {{ $s->id === $service->id ? 'bg-brand-50 text-brand-700 border border-brand-200' : 'text-slate-700 hover:bg-slate-50' }}">
                                <span class="line-clamp-1">{{ $s->title }}</span>
                                <i class="fa-solid fa-chevron-right text-xs text-slate-400"></i>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Contact Card -->
                    <div class="bg-navy-950 text-white p-6 rounded-2xl border border-slate-800 space-y-4">
                        <div class="w-10 h-10 rounded-xl bg-brand-600 flex items-center justify-center text-white text-lg">
                            <i class="fa-solid fa-headset"></i>
                        </div>
                        <h3 class="font-bold text-lg">Konsultasi Kebutuhan Armada</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Hubungi hotline kami kapan saja untuk konsultasi cepat via WhatsApp atau telepon langsung.
                        </p>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['company_whatsapp'] ?? '6281188881234') }}" target="_blank" class="block text-center py-3 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs uppercase tracking-wider transition">
                            <i class="fa-brands fa-whatsapp mr-1.5 text-base"></i> Hubungi WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
