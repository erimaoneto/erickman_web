@extends('layouts.app')

@section('title', ($settings['company_name'] ?? 'PT Erickman') . ' - ' . ($settings['company_tagline'] ?? 'Solusi Transportasi Gas Alam & Logistik Khusus'))

@section('content')

    <!-- Flash Message Notification -->
    @if(session('success_inquiry'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start gap-3 shadow-sm">
            <i class="fa-solid fa-circle-check text-emerald-600 text-xl mt-0.5"></i>
            <div>
                <h4 class="font-bold text-sm">Permintaan Terkirim!</h4>
                <p class="text-sm text-emerald-700 mt-0.5">{{ session('success_inquiry') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Hero Slider Section -->
    <section id="beranda" class="relative bg-navy-950 overflow-hidden" 
             x-data="{
                activeSlide: 0,
                slidesCount: {{ count($banners) }},
                autoplay: null,
                startAutoplay() {
                    this.autoplay = setInterval(() => {
                        this.activeSlide = (this.activeSlide + 1) % this.slidesCount;
                    }, 6500);
                },
                stopAutoplay() {
                    clearInterval(this.autoplay);
                }
             }" 
             x-init="startAutoplay()"
             @mouseenter="stopAutoplay()"
             @mouseleave="startAutoplay()">
        
        <div class="relative min-h-[580px] lg:min-h-[660px] flex items-center">
            @foreach($banners as $index => $banner)
            <div x-show="activeSlide === {{ $index }}" 
                 x-transition:enter="transition ease-out duration-700"
                 x-transition:enter-start="opacity-0 scale-105"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-500"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0 w-full h-full"
                 style="{{ $index !== 0 ? 'display: none;' : '' }}">
                
                <!-- Background Image (Bright, Natural & Clear) -->
                <img src="{{ asset($banner->image_path) }}" alt="{{ $banner->title }}" class="w-full h-full object-cover object-center brightness-100">
                <!-- Soft ambient gradient on the left side only to keep the truck on the right clear and vibrant -->
                <div class="absolute inset-0 bg-gradient-to-r from-navy-950/80 via-navy-950/30 to-transparent"></div>
                <div class="absolute inset-x-0 bottom-0 h-28 bg-gradient-to-t from-navy-950/80 via-transparent to-transparent"></div>

                <!-- Slide Content in Sleek Frosted Glass Container -->
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center pt-8 pb-16">
                    <div class="max-w-xl lg:max-w-2xl text-white space-y-5 bg-navy-950/70 backdrop-blur-md p-6 sm:p-8 rounded-3xl border border-white/15 shadow-2xl">
                        @if($banner->tagline)
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-brand-500/20 border border-brand-400/30 text-brand-300 text-xs font-semibold uppercase tracking-wider backdrop-blur-sm">
                            <span class="w-2 h-2 rounded-full bg-brand-400 animate-pulse"></span>
                            {{ $banner->tagline }}
                        </div>
                        @endif

                        <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight">
                            {{ $banner->title }}
                        </h1>

                        <p class="text-sm sm:text-base text-slate-200 leading-relaxed font-normal">
                            {{ $banner->description }}
                        </p>

                        <div class="pt-2 flex flex-wrap items-center gap-3.5">
                            @if($banner->button_text)
                            <a href="{{ $banner->button_url ?? '#kontak' }}" class="px-6 py-3 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs sm:text-sm shadow-lg shadow-brand-600/30 hover:shadow-brand-500/50 transition transform hover:-translate-y-0.5 flex items-center gap-2">
                                <span>{{ $banner->button_text }}</span>
                                <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                            @endif
                            <a href="#tentang" class="px-6 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs sm:text-sm backdrop-blur-md border border-white/20 transition">
                                Profil Perusahaan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Slider Controls & Dots -->
        <div class="absolute bottom-6 inset-x-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                <!-- Dots Indicator -->
                <div class="flex items-center gap-2.5">
                    @foreach($banners as $index => $banner)
                    <button @click="activeSlide = {{ $index }}" 
                            :class="activeSlide === {{ $index }} ? 'w-10 bg-brand-500' : 'w-2.5 bg-white/40 hover:bg-white/70'" 
                            class="h-2.5 rounded-full transition-all duration-300"
                            title="Slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>

                <!-- Prev/Next Navigation -->
                <div class="flex items-center gap-2">
                    <button @click="activeSlide = (activeSlide - 1 + slidesCount) % slidesCount" 
                            class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/25 text-white flex items-center justify-center backdrop-blur-md border border-white/20 transition">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </button>
                    <button @click="activeSlide = (activeSlide + 1) % slidesCount" 
                            class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/25 text-white flex items-center justify-center backdrop-blur-md border border-white/20 transition">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Key Metrics & Counter Banner -->
    <section class="relative z-20 -mt-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-100 p-6 sm:p-8 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="border-r border-slate-100 last:border-0 p-2">
                <div class="text-3xl sm:text-4xl font-black text-brand-600 tracking-tight">{{ $settings['stat_fleet_count'] ?? '50+' }}</div>
                <div class="text-xs sm:text-sm font-semibold text-slate-500 mt-1 uppercase tracking-wider">Unit Armada Prima</div>
            </div>
            <div class="border-r border-slate-100 last:border-0 p-2">
                <div class="text-3xl sm:text-4xl font-black text-navy-900 tracking-tight">{{ $settings['stat_cng_delivered'] ?? '1.500.000+' }}</div>
                <div class="text-xs sm:text-sm font-semibold text-slate-500 mt-1 uppercase tracking-wider">MMSCF Gas Terdistribusi</div>
            </div>
            <div class="border-r border-slate-100 last:border-0 p-2">
                <div class="text-3xl sm:text-4xl font-black text-brand-600 tracking-tight">{{ $settings['stat_ontime_rate'] ?? '99.4%' }}</div>
                <div class="text-xs sm:text-sm font-semibold text-slate-500 mt-1 uppercase tracking-wider">Ketepatan Waktu</div>
            </div>
            <div class="p-2">
                <div class="text-3xl sm:text-4xl font-black text-emerald-600 tracking-tight">{{ $settings['stat_safety_record'] ?? '100%' }}</div>
                <div class="text-xs sm:text-sm font-semibold text-slate-500 mt-1 uppercase tracking-wider">Zero Accident (HSE)</div>
            </div>
        </div>
    </section>

    <!-- Tentang Kami & Legalitas Resmi (NIB) -->
    <section id="tentang" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Visual / Photo Collage -->
                <div class="lg:col-span-6 relative">
                    <div class="relative z-10 rounded-2xl overflow-hidden shadow-2xl border-4 border-white">
                        <img src="{{ asset('/images/truck-cng-green.jpg') }}" alt="Truk CNG PT Erickman" class="w-full h-80 sm:h-96 object-cover">
                    </div>
                    <!-- Secondary Floating Image -->
                    <div class="hidden sm:block absolute -bottom-8 -right-6 z-20 w-64 rounded-xl overflow-hidden shadow-2xl border-4 border-white">
                        <img src="{{ asset('/images/truck-box-red.jpg') }}" alt="Armada Boks PT Erickman" class="w-full h-44 object-cover">
                    </div>
                    <!-- Floating Badge NIB -->
                    <div class="absolute -top-6 -left-6 z-30 bg-navy-900 text-white p-5 rounded-2xl shadow-xl border border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-brand-600 flex items-center justify-center text-white text-xl">
                                <i class="fa-solid fa-stamp"></i>
                            </div>
                            <div>
                                <span class="block text-[11px] uppercase tracking-wider text-slate-400 font-semibold">Perizinan Berusaha</span>
                                <span class="block text-base font-extrabold text-white">NIB Resmi Terverifikasi</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Story & Compliance Content -->
                <div class="lg:col-span-6 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-brand-100 text-brand-800 text-xs font-bold uppercase tracking-wider">
                        <i class="fa-solid fa-building-shield"></i> Legalitas & Integritas Terjamin
                    </div>

                    <h2 class="text-3xl sm:text-4xl font-extrabold text-navy-900 tracking-tight leading-tight">
                        Mitra Transportasi Energi & Rantai Pasok Terdepan
                    </h2>

                    <p class="text-slate-600 leading-relaxed text-base">
                        {{ $settings['about_story'] ?? 'Didirikan dengan komitmen kuat terhadap efisiensi energi dan logistik industri di Indonesia, PT Erickman beroperasi di bawah legalitas Perizinan Berusaha Berbasis Risiko (NIB: 2211210015706).' }}
                    </p>

                    <!-- Office Location Badge -->
                    <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-sm flex items-start gap-3">
                        <i class="fa-solid fa-location-dot text-brand-600 text-xl mt-1"></i>
                        <div>
                            <h4 class="font-bold text-sm text-navy-900">Kantor Pusat Resmi:</h4>
                            <p class="text-xs text-slate-600 mt-0.5 leading-normal">
                                {{ $settings['company_address'] ?? '18 Office Park Building, 12th Floor Unit A & H, Jl. TB Simatupang No.18, Jakarta Selatan 12520' }}
                            </p>
                        </div>
                    </div>

                    <!-- Vision & Mission Tabs -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div class="p-5 rounded-xl bg-emerald-50/70 border border-emerald-100">
                            <div class="flex items-center gap-2 text-emerald-800 font-bold text-sm mb-2">
                                <i class="fa-solid fa-bullseye"></i> Visi Kami
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                {{ $settings['company_vision'] ?? 'Menjadi perusahaan transportasi energi gas dan logistik khusus nomor satu yang mengutamakan keselamatan dan kepuasan pelanggan.' }}
                            </p>
                        </div>

                        <div class="p-5 rounded-xl bg-sky-50/70 border border-sky-100">
                            <div class="flex items-center gap-2 text-sky-800 font-bold text-sm mb-2">
                                <i class="fa-solid fa-compass"></i> Misi Kami
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Standar HSE tertinggi, perawatan armada tepat waktu, kehandalan rantai pasok energi bersih tanpa henti.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Layanan & KBLI Resmi Perusahaan -->
    <section id="layanan" class="py-24 bg-white border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-brand-100 text-brand-800 text-xs font-bold uppercase tracking-wider">
                    Solusi Komprehensif
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-navy-900 tracking-tight">
                    Layanan & Klasifikasi Bidang Usaha (KBLI)
                </h2>
                <p class="text-slate-600 text-sm sm:text-base">
                    Seluruh operasional kami memiliki izin usaha legal berbasis risiko yang diterbitkan oleh Pemerintah Republik Indonesia melalui sistem OSS.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($services as $service)
                <div class="group bg-slate-50 rounded-2xl overflow-hidden border border-slate-200/70 hover:border-brand-500/50 hover:shadow-xl transition duration-300 flex flex-col justify-between">
                    <div>
                        <!-- Service Image -->
                        <div class="h-48 overflow-hidden relative">
                            <img src="{{ asset($service->image_path ?? '/images/truck-cng-green.jpg') }}" alt="{{ $service->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @if($service->kbli_code)
                            <div class="absolute top-3 left-3 bg-navy-900/90 backdrop-blur-sm text-brand-400 font-mono text-[11px] font-bold px-2.5 py-1 rounded border border-slate-700">
                                {{ $service->kbli_code }}
                            </div>
                            @endif
                        </div>

                        <!-- Service Body -->
                        <div class="p-6 space-y-3">
                            <h3 class="font-bold text-lg text-navy-900 group-hover:text-brand-600 transition leading-snug">
                                {{ $service->title }}
                            </h3>
                            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                                {{ $service->short_description }}
                            </p>
                        </div>
                    </div>

                    <div class="p-6 pt-0">
                        <a href="{{ route('service.detail', $service->slug) }}" class="inline-flex items-center gap-2 text-xs font-bold text-brand-600 hover:text-brand-700 uppercase tracking-wider">
                            <span>Pelajari Selengkapnya</span>
                            <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition transform"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Armada Kami (Fleet Showcase) -->
    <section id="armada" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-16">
                <div class="max-w-2xl space-y-3">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-brand-100 text-brand-800 text-xs font-bold uppercase tracking-wider">
                        Armada Andal & Tangguh
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-navy-900 tracking-tight">
                        Spesifikasi Kendaraan Operasional
                    </h2>
                    <p class="text-slate-600 text-sm sm:text-base">
                        Didukung oleh armada modern dengan perawatan berkala, sertifikasi uji KIR aktif, dan pengawasan GPS 24 jam.
                    </p>
                </div>
                <div>
                    <a href="#kontak" class="px-6 py-3 rounded-xl bg-navy-900 hover:bg-navy-800 text-white font-semibold text-xs uppercase tracking-wider transition">
                        Reservasi Unit Armada
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($fleets as $fleet)
                <div class="bg-white rounded-2xl overflow-hidden border border-slate-200/80 shadow-sm hover:shadow-md transition">
                    <div class="h-52 overflow-hidden relative bg-slate-100">
                        <img src="{{ asset($fleet->image_path ?? '/images/truck-cng-green.jpg') }}" alt="{{ $fleet->vehicle_name }}" class="w-full h-full object-cover">
                        <div class="absolute top-3 right-3">
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $fleet->status === 'Tersedia' ? 'bg-emerald-500 text-white' : ($fleet->status === 'Dalam Perjalanan' ? 'bg-blue-500 text-white' : 'bg-amber-500 text-white') }}">
                                {{ $fleet->status }}
                            </span>
                        </div>
                        <div class="absolute bottom-3 left-3 bg-navy-900/90 text-white font-mono text-xs font-bold px-2.5 py-1 rounded border border-slate-700">
                            {{ $fleet->plate_number }}
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        <div>
                            <div class="text-[11px] font-bold text-brand-600 uppercase tracking-wider">{{ $fleet->type }}</div>
                            <h3 class="font-bold text-lg text-navy-900 mt-0.5">{{ $fleet->vehicle_name }}</h3>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-xs py-3 border-y border-slate-100">
                            <div>
                                <span class="text-slate-400 block">Merk & Model:</span>
                                <span class="font-semibold text-slate-700">{{ $fleet->brand ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block">Kapasitas:</span>
                                <span class="font-semibold text-slate-700">{{ $fleet->capacity ?? '-' }}</span>
                            </div>
                            <div class="mt-1">
                                <span class="text-slate-400 block">Tahun Unit:</span>
                                <span class="font-semibold text-slate-700">{{ $fleet->year ?? '-' }}</span>
                            </div>
                            <div class="mt-1">
                                <span class="text-slate-400 block">Standar Uji KIR:</span>
                                <span class="font-semibold text-emerald-600"><i class="fa-solid fa-circle-check text-[10px]"></i> Terverifikasi</span>
                            </div>
                        </div>

                        <p class="text-xs text-slate-500 line-clamp-2">
                            {{ $fleet->notes ?? 'Unit operasional terawat berkala dengan fasilitas pelacakan dispatch.' }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Standar Keselamatan (HSE & K3) -->
    <section id="keunggulan" class="py-24 bg-navy-950 text-white relative overflow-hidden">
        <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-brand-600/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-brand-500/20 text-brand-300 text-xs font-bold uppercase tracking-wider">
                    Safety First (HSE)
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                    Komitmen Keselamatan & Keamanan Tertinggi
                </h2>
                <p class="text-slate-400 text-sm sm:text-base">
                    Transportasi gas bertekanan dan kargo khusus menuntut kepatuhan protokol tanpa toleransi kesalahan.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-slate-900/80 p-6 rounded-2xl border border-slate-800 space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-brand-600/20 text-brand-400 flex items-center justify-center text-2xl font-bold">
                        <i class="fa-solid fa-gauge-high"></i>
                    </div>
                    <h3 class="font-bold text-base text-white">Inspeksi Tekanan CNG 250 Bar</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Pemeriksaan manifold pipa, katup pengaman (safety relief valve), dan integritas silinder gas sebelum dispatch.
                    </p>
                </div>

                <div class="bg-slate-900/80 p-6 rounded-2xl border border-slate-800 space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-emerald-600/20 text-emerald-400 flex items-center justify-center text-2xl font-bold">
                        <i class="fa-solid fa-id-card-clip"></i>
                    </div>
                    <h3 class="font-bold text-base text-white">Driver Bersertifikasi B3</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Seluruh pengemudi dibekali pelatihan defensive driving, sertifikasi penanganan gas berbahaya, dan bebas narkoba.
                    </p>
                </div>

                <div class="bg-slate-900/80 p-6 rounded-2xl border border-slate-800 space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-sky-600/20 text-sky-400 flex items-center justify-center text-2xl font-bold">
                        <i class="fa-solid fa-satellite"></i>
                    </div>
                    <h3 class="font-bold text-base text-white">GPS Telematika 24/7</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Sistem pelacakan rute real-time, monitoring kecepatan armada, serta komunikasi kontrol pool terpusat.
                    </p>
                </div>

                <div class="bg-slate-900/80 p-6 rounded-2xl border border-slate-800 space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-red-600/20 text-red-400 flex items-center justify-center text-2xl font-bold">
                        <i class="fa-solid fa-fire-extinguisher"></i>
                    </div>
                    <h3 class="font-bold text-base text-white">Protokol Tanggap Darurat</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Setiap unit dilengkapi APAR gas khusus, grounding antistatik, kotak P3K, dan tim tanggap insiden 24 jam.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Formulir Permintaan Penawaran & Kontak -->
    <section id="kontak" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <!-- Kontak Info & Peta Info -->
                <div class="lg:col-span-5 space-y-8">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-brand-100 text-brand-800 text-xs font-bold uppercase tracking-wider mb-3">
                            Hubungi Kami
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-navy-900 tracking-tight leading-tight">
                            Minta Penawaran Resmi & Konsultasi Armada
                        </h2>
                        <p class="text-slate-600 text-sm mt-3 leading-relaxed">
                            Hubungi tim sales dan operasi kami untuk mendiskusikan kebutuhan pasokan gas alam, kontrak pengiriman khusus, atau persewaan truk komersial.
                        </p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50 border border-slate-200">
                            <div class="w-10 h-10 rounded-lg bg-brand-600 text-white flex items-center justify-center text-lg flex-shrink-0">
                                <i class="fa-solid fa-building"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-navy-900">Alamat Kantor Pusat</h4>
                                <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                                    {{ $settings['company_address'] ?? '18 Office Park Building, 12th Floor Unit A & H, Jl. TB Simatupang No.18, RT 002 RW 001, Kel. Kebagusan, Kec. Pasar Minggu, Jakarta Selatan 12520' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50 border border-slate-200">
                            <div class="w-10 h-10 rounded-lg bg-emerald-600 text-white flex items-center justify-center text-lg flex-shrink-0">
                                <i class="fa-brands fa-whatsapp"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-navy-900">Hotline WhatsApp & Telepon</h4>
                                <p class="text-xs text-slate-600 mt-1">
                                    WhatsApp: <strong class="text-emerald-700">{{ $settings['company_whatsapp'] ?? '+62 811 8888 1234' }}</strong><br>
                                    Telp Kantor: {{ $settings['company_phone'] ?? '+62 21 2278 1818' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50 border border-slate-200">
                            <div class="w-10 h-10 rounded-lg bg-sky-600 text-white flex items-center justify-center text-lg flex-shrink-0">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-navy-900">Korespondensi Email</h4>
                                <p class="text-xs text-slate-600 mt-1">
                                    {{ $settings['company_email'] ?? 'info@erickman.co.id' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Card -->
                <div class="lg:col-span-7">
                    <div class="bg-slate-50 p-8 sm:p-10 rounded-3xl border border-slate-200 shadow-lg shadow-slate-100">
                        <h3 class="font-bold text-xl text-navy-900 mb-2">Formulir Permintaan Penawaran (RFQ)</h3>
                        <p class="text-xs text-slate-500 mb-6">Silakan lengkapi rincian kebutuhan Anda di bawah ini, pesan akan langsung masuk ke Admin Panel kami.</p>

                        <form action="{{ route('inquiry.submit') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap *</label>
                                    <input type="text" name="name" required placeholder="Contoh: Budi Santoso" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm bg-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Perusahaan / Instansi</label>
                                    <input type="text" name="company" placeholder="Contoh: PT Manufaktur Jaya" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm bg-white">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Alamat Email *</label>
                                    <input type="email" name="email" required placeholder="nama@perusahaan.co.id" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm bg-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">No. WhatsApp / HP</label>
                                    <input type="text" name="phone" placeholder="0812xxxxxxx" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm bg-white">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Layanan yang Diminati</label>
                                    <select name="service_interest" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm bg-white">
                                        <option value="">-- Pilih Layanan --</option>
                                        @foreach($services as $s)
                                        <option value="{{ $s->title }}">{{ $s->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Subjek Permintaan *</label>
                                    <input type="text" name="subject" required placeholder="Contoh: Penawaran Pasokan Gas CNG" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm bg-white">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Detail Kebutuhan / Pesan *</label>
                                <textarea name="message" rows="4" required placeholder="Tuliskan detail muatan, volume kubikasi gas, rute tujuan, durasi sewa, atau spesifikasi yang diinginkan..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm bg-white"></textarea>
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="w-full py-3.5 px-6 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm tracking-wide shadow-md shadow-brand-600/30 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                    <i class="fa-regular fa-paper-plane"></i>
                                    <span>Kirim Permintaan Penawaran Sekarang</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
