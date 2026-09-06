@extends('layouts.app')

@section('title', ($settings['company_name'] ?? 'PT Erickman') . ' - ' . ($settings['company_tagline'] ?? 'Solusi Transportasi Gas Alam & Logistik Khusus'))

@section('content')

    <!-- Flash Message Notification -->
    @if(session('success_inquiry'))
    <div class="max-w-7xl xl:max-w-[1360px] 2xl:max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-10 pt-6">
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
        
        <div class="relative min-h-[540px] sm:min-h-[580px] lg:min-h-[640px] 2xl:min-h-[700px] flex items-center">
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
                <div class="absolute inset-0 bg-gradient-to-r from-navy-950/90 sm:from-navy-950/80 via-navy-950/50 sm:via-navy-950/30 to-transparent"></div>
                <div class="absolute inset-x-0 bottom-0 h-28 bg-gradient-to-t from-navy-950/80 via-transparent to-transparent"></div>

                <!-- Slide Content in Sleek Frosted Glass Container -->
                <div class="relative max-w-7xl xl:max-w-[1360px] 2xl:max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-10 h-full flex items-center pt-6 pb-20 sm:pt-8 sm:pb-16">
                    <div class="max-w-xl lg:max-w-2xl 2xl:max-w-3xl text-white space-y-4 sm:space-y-5 bg-navy-950/80 sm:bg-navy-950/75 backdrop-blur-md p-5 sm:p-8 lg:p-10 rounded-2xl sm:rounded-3xl border border-white/15 shadow-2xl">
                        @if($banner->tagline)
                        <div class="inline-flex items-center gap-2 px-3 py-1 sm:px-3.5 sm:py-1.5 rounded-full bg-brand-500/20 border border-brand-400/30 text-brand-300 text-[11px] sm:text-xs font-semibold uppercase tracking-wider backdrop-blur-sm">
                            <span class="w-2 h-2 rounded-full bg-brand-400 animate-pulse"></span>
                            {{ $banner->tagline }}
                        </div>
                        @endif

                        <h1 class="text-xl sm:text-3xl lg:text-5xl font-extrabold tracking-tight leading-snug sm:leading-tight">
                            {{ $banner->title }}
                        </h1>

                        <p class="text-xs sm:text-base text-slate-200 leading-relaxed font-normal line-clamp-3 sm:line-clamp-none">
                            {{ $banner->description }}
                        </p>

                        <div class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 sm:gap-3.5 w-full sm:w-auto">
                            @if($banner->button_text)
                            <a href="{{ $banner->button_url ?? '#kontak' }}" class="px-5 py-2.5 sm:px-6 sm:py-3 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs sm:text-sm shadow-lg shadow-brand-600/30 hover:shadow-brand-500/50 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                <span>{{ $banner->button_text }}</span>
                                <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                            @endif
                            <a href="#tentang" class="px-5 py-2.5 sm:px-6 sm:py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs sm:text-sm backdrop-blur-md border border-white/20 transition text-center justify-center">
                                Profil Perusahaan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Slider Controls & Dots -->
        <div class="absolute bottom-4 sm:bottom-6 inset-x-0 z-20">
            <div class="max-w-7xl xl:max-w-[1360px] 2xl:max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-10 flex items-center justify-between">
                <!-- Dots Indicator -->
                <div class="flex items-center gap-2 sm:gap-2.5">
                    @foreach($banners as $index => $banner)
                    <button @click="activeSlide = {{ $index }}" 
                            :class="activeSlide === {{ $index }} ? 'w-8 sm:w-10 bg-brand-500' : 'w-2 sm:w-2.5 bg-white/40 hover:bg-white/70'" 
                            class="h-2 sm:h-2.5 rounded-full transition-all duration-300"
                            title="Slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>

                <!-- Prev/Next Navigation -->
                <div class="flex items-center gap-2">
                    <button @click="activeSlide = (activeSlide - 1 + slidesCount) % slidesCount" 
                            class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-white/10 hover:bg-white/25 text-white flex items-center justify-center backdrop-blur-md border border-white/20 transition">
                        <i class="fa-solid fa-chevron-left text-[10px] sm:text-xs"></i>
                    </button>
                    <button @click="activeSlide = (activeSlide + 1) % slidesCount" 
                            class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-white/10 hover:bg-white/25 text-white flex items-center justify-center backdrop-blur-md border border-white/20 transition">
                        <i class="fa-solid fa-chevron-right text-[10px] sm:text-xs"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Key Metrics & Counter Banner -->
    @if(($settings['show_stats_section'] ?? '1') == '1' && isset($keyMetrics) && $keyMetrics->count() > 0)
    <section class="relative z-20 -mt-6 sm:-mt-8 max-w-7xl xl:max-w-[1360px] 2xl:max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-10">
        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-100 p-4 sm:p-8 grid grid-cols-2 md:grid-cols-{{ min(4, $keyMetrics->count()) }} gap-3 sm:gap-6 text-center">
            @foreach($keyMetrics as $km)
            <div class="p-2 sm:p-3 {{ ($loop->iteration % 2 == 1) ? 'border-r border-slate-100 md:border-r' : 'md:border-r' }} {{ $loop->last ? 'md:border-0' : '' }}">
                <div class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight {{ $km->color_theme === 'emerald' ? 'text-emerald-600' : ($km->color_theme === 'navy' ? 'text-navy-900' : ($km->color_theme === 'sky' ? 'text-sky-600' : 'text-brand-600')) }}">
                    {{ $km->number_value }}
                </div>
                <div class="text-[11px] sm:text-sm font-semibold text-slate-500 mt-1 uppercase tracking-wider">{{ $km->label }}</div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Tentang Kami & Legalitas Resmi (NIB) -->
    <section id="tentang" class="py-12 sm:py-20 lg:py-24 bg-slate-50">
        <div class="max-w-7xl xl:max-w-[1360px] 2xl:max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 sm:gap-12 items-center">
                <!-- Visual / Photo Collage -->
                <div class="lg:col-span-6 relative">
                    <div class="relative z-10 rounded-2xl overflow-hidden shadow-xl sm:shadow-2xl border-4 border-white">
                        <img src="{{ asset($settings['about_image_main'] ?? '/images/truck-cng-green.jpg') }}" alt="Foto Utama PT Erickman" class="w-full h-64 sm:h-96 object-cover">
                    </div>
                    <!-- Secondary Floating Image -->
                    @if(($settings['show_about_secondary_image'] ?? '1') == '1')
                    <div class="hidden sm:block absolute -bottom-8 -right-6 z-20 w-64 rounded-xl overflow-hidden shadow-2xl border-4 border-white">
                        <img src="{{ asset($settings['about_image_secondary'] ?? '/images/truck-box-red.jpg') }}" alt="Foto Armada PT Erickman" class="w-full h-44 object-cover">
                    </div>
                    @endif
                    <!-- Floating Badge NIB -->
                    <div class="absolute -top-4 left-3 sm:-top-6 sm:-left-6 z-30 bg-navy-900 text-white p-3.5 sm:p-5 rounded-xl sm:rounded-2xl shadow-xl border border-slate-800">
                        <div class="flex items-center gap-2.5 sm:gap-3">
                            <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-brand-600 flex items-center justify-center text-white text-base sm:text-xl shrink-0">
                                <i class="fa-solid fa-stamp"></i>
                            </div>
                            <div>
                                <span class="block text-[9px] sm:text-[11px] uppercase tracking-wider text-slate-400 font-semibold">Perizinan Berusaha</span>
                                <span class="block text-xs sm:text-base font-extrabold text-white">NIB Resmi Terverifikasi</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Story & Compliance Content -->
                <div class="lg:col-span-6 space-y-5 sm:space-y-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-brand-100 text-brand-800 text-xs font-bold uppercase tracking-wider">
                        <i class="fa-solid fa-building-shield"></i> {{ $settings['about_badge_text'] ?? 'Legalitas & Integritas Terjamin' }}
                    </div>

                    <h2 class="text-2xl sm:text-4xl font-extrabold text-navy-900 tracking-tight leading-snug sm:leading-tight">
                        {{ $settings['about_heading_text'] ?? 'Pendistribusian Gas LPG, CNG, & Transportasi Migas' }}
                    </h2>

                    <p class="text-slate-600 leading-relaxed text-sm sm:text-base">
                        {{ $settings['about_story'] ?? 'PT. Erickman Sarana Abadi berkedudukan dan berkantor pusat di kota Jakarta adalah perusahaan yang bergerak di bidang pendistribusian gas LPG (Liquified Petroleum Gas) dan CNG (Compressed Natural Gas), juga penyedia sarana transportasi migas.' }}
                    </p>



                    <!-- Vision & Mission Tabs -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div class="p-5 rounded-xl bg-emerald-50/70 border border-emerald-100">
                            <div class="flex items-center gap-2 text-emerald-800 font-bold text-sm mb-2">
                                <i class="fa-solid fa-bullseye"></i> Visi Kami
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                {{ $settings['company_vision'] ?? 'Menjadi mitra terdepan dan terpercaya dalam distribusi gas LPG, CNG, serta penyedia armada transportasi migas dan komoditas energi dengan standar keselamatan dan kehandalan tertinggi di Indonesia.' }}
                            </p>
                        </div>

                        <div class="p-5 rounded-xl bg-sky-50/70 border border-sky-100">
                            <div class="flex items-center gap-2 text-sky-800 font-bold text-sm mb-2">
                                <i class="fa-solid fa-compass"></i> Misi Kami
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Standar HSE & K3LL tanpa kompromi, kepuasan pelanggan melalui pasokan energi stabil dan tepat waktu di seluruh Indonesia.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Armada Kami (Fleet Showcase) -->
    @if(($settings['show_armada_section'] ?? '1') == '1')
    <section id="armada" class="py-12 sm:py-20 lg:py-24 bg-slate-50">
        <div class="max-w-7xl xl:max-w-[1360px] 2xl:max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-10">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10 sm:mb-16">
                <div class="max-w-2xl space-y-3">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-brand-100 text-brand-800 text-xs font-bold uppercase tracking-wider">
                        {{ $settings['section_armada_badge'] ?? 'Armada Andal & Tangguh' }}
                    </div>
                    <h2 class="text-2xl sm:text-4xl font-extrabold text-navy-900 tracking-tight leading-snug sm:leading-tight">
                        {{ $settings['section_armada_title'] ?? 'Spesifikasi Kendaraan Operasional' }}
                    </h2>
                    <p class="text-slate-600 text-xs sm:text-base leading-relaxed">
                        {{ $settings['section_armada_desc'] ?? 'Didukung oleh armada modern dengan perawatan berkala, sertifikasi uji KIR aktif, dan pengawasan GPS 24 jam.' }}
                    </p>
                </div>
                <div>
                    <a href="#kontak" class="w-full sm:w-auto inline-block text-center px-6 py-3 rounded-xl bg-navy-900 hover:bg-navy-800 text-white font-semibold text-xs uppercase tracking-wider transition">
                        Reservasi Unit Armada
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach($fleets as $fleet)
                <div class="bg-white rounded-2xl overflow-hidden border border-slate-200/80 shadow-sm hover:shadow-md transition">
                    <div class="h-48 sm:h-52 overflow-hidden relative bg-slate-100">
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

                    <div class="p-5 sm:p-6 space-y-3.5 sm:space-y-4">
                        <div>
                            <div class="text-[11px] font-bold text-brand-600 uppercase tracking-wider">{{ $fleet->type }}</div>
                            <h3 class="font-bold text-base sm:text-lg text-navy-900 mt-0.5">{{ $fleet->vehicle_name }}</h3>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-[11px] sm:text-xs py-2.5 sm:py-3 border-y border-slate-100">
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
    @endif

    <!-- Standar Keselamatan (HSE & K3) -->
    @if(($settings['show_hse_section'] ?? '1') == '1' && isset($hseItems) && $hseItems->count() > 0)
    <section id="keunggulan" class="py-12 sm:py-20 lg:py-24 bg-navy-950 text-white relative overflow-hidden">
        <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-brand-600/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="max-w-7xl xl:max-w-[1360px] 2xl:max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-10 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-16 space-y-3">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-brand-500/20 text-brand-300 text-xs font-bold uppercase tracking-wider">
                    {{ $settings['section_hse_badge'] ?? 'Safety First (HSE)' }}
                </div>
                <h2 class="text-2xl sm:text-4xl font-extrabold tracking-tight leading-snug sm:leading-tight">
                    {{ $settings['section_hse_title'] ?? 'Komitmen Keselamatan & Keamanan Tertinggi' }}
                </h2>
                <p class="text-slate-400 text-xs sm:text-base leading-relaxed">
                    {{ $settings['section_hse_desc'] ?? 'Transportasi gas bertekanan dan kargo khusus menuntut kepatuhan protokol tanpa toleransi kesalahan.' }}
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ min(4, max(1, count($hseItems))) }} gap-4 sm:gap-6">
                @foreach($hseItems as $hse)
                <div class="bg-slate-900/80 p-5 sm:p-6 rounded-2xl border border-slate-800 space-y-3">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center text-xl sm:text-2xl font-bold {{ $hse->color_theme === 'emerald' ? 'bg-emerald-600/20 text-emerald-400' : ($hse->color_theme === 'sky' ? 'bg-sky-600/20 text-sky-400' : ($hse->color_theme === 'red' ? 'bg-red-600/20 text-red-400' : 'bg-brand-600/20 text-brand-400')) }}">
                        <i class="{{ $hse->icon }}"></i>
                    </div>
                    <h3 class="font-bold text-sm sm:text-base text-white">{{ $hse->title }}</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        {{ $hse->description }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Rekanan Kami (Partner & Client Network) -->
    @if(($settings['show_rekanan_section'] ?? '1') == '1' && isset($partners) && $partners->count() > 0)
    <section id="rekanan" class="py-12 sm:py-20 lg:py-24 bg-slate-50 border-t border-slate-200">
        <div class="max-w-7xl xl:max-w-[1360px] 2xl:max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-10">
            <div class="text-center max-w-3xl mx-auto mb-8 sm:mb-14 space-y-3">
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-brand-100 text-brand-800 text-xs font-bold uppercase tracking-wider">
                    <i class="fa-solid fa-handshake-simple text-xs"></i> {{ $settings['section_rekanan_badge'] ?? 'Rekanan & Kemitraan Strategis' }}
                </div>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-navy-900 tracking-tight leading-snug sm:leading-tight">
                    {{ $settings['section_rekanan_title'] ?? 'Rekanan Kami' }}
                </h2>
                <p class="text-slate-600 text-xs sm:text-base leading-relaxed">
                    {{ $settings['section_rekanan_desc'] ?? 'PT. Erickman Sarana Abadi dipercaya oleh berbagai perusahaan energi dan logistik terkemuka dalam rantai pasok gas dan transportasi migas.' }}
                </p>
            </div>

            <!-- Partner Cards List -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-{{ min(6, max(2, count($partners))) }} gap-3 sm:gap-6">
                @foreach($partners as $partner)
                <div class="bg-white p-3.5 sm:p-5 rounded-xl sm:rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-brand-500 transition text-center flex flex-col items-center justify-center min-h-[130px] sm:min-h-[150px] group">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center text-lg sm:text-xl font-black mb-2 sm:mb-2.5 group-hover:scale-110 transition {{ $partner->color_theme === 'orange' ? 'bg-orange-50 text-orange-600' : ($partner->color_theme === 'red' ? 'bg-red-50 text-red-600' : ($partner->color_theme === 'sky' ? 'bg-sky-50 text-sky-600' : ($partner->color_theme === 'emerald' ? 'bg-emerald-50 text-emerald-600' : ($partner->color_theme === 'amber' ? 'bg-amber-50 text-amber-600' : 'bg-blue-50 text-blue-600')))) }}">
                        @if($partner->logo_path)
                        <img src="{{ asset($partner->logo_path) }}" alt="{{ $partner->name }}" class="max-h-7 max-w-7 sm:max-h-8 sm:max-w-8 object-contain">
                        @else
                        <i class="{{ $partner->icon ?: 'fa-solid fa-building' }}"></i>
                        @endif
                    </div>
                    <h4 class="font-extrabold text-xs sm:text-sm text-navy-900 leading-tight">{{ $partner->name }}</h4>
                    @if($partner->subtitle)
                    <span class="text-[10px] sm:text-[11px] text-slate-500 mt-1 line-clamp-2 leading-tight">{{ $partner->subtitle }}</span>
                    @endif
                </div>
                @endforeach
            </div>

            @if(!empty($settings['rekanan_banner_image']))
            <!-- Banner Logo Asli Dokumen Company Profile PDF -->
            <div class="mt-8 sm:mt-10 bg-white p-4 sm:p-8 rounded-2xl border border-slate-200 text-center max-w-4xl mx-auto shadow-sm">
                <div class="text-[10px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-3 sm:mb-4">Logo Rekanan Resmi Sesuai Profil Perusahaan</div>
                <img src="{{ asset($settings['rekanan_banner_image']) }}" alt="Logo Rekanan Resmi PT Erickman Sarana Abadi" class="mx-auto max-h-16 sm:max-h-24 w-auto object-contain">
            </div>
            @endif
        </div>
    </section>
    @endif

    <!-- Kontak & Kantor Operasional -->
    <section id="kontak" class="py-12 sm:py-20 lg:py-24 bg-white">
        <div class="max-w-7xl xl:max-w-[1360px] 2xl:max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-10">
            <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-16">
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-brand-100 text-brand-800 text-xs font-bold uppercase tracking-wider mb-3">
                    <i class="fa-solid fa-headset text-xs"></i> {{ $settings['section_kontak_badge'] ?? 'Hubungi Kami' }}
                </div>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-navy-900 tracking-tight leading-snug sm:leading-tight">
                    {{ $settings['section_kontak_title'] ?? 'Kontak Resmi & Kantor Operasional' }}
                </h2>
                <p class="text-slate-600 text-xs sm:text-sm mt-3 leading-relaxed">
                    {{ $settings['section_kontak_desc'] ?? 'Hubungi tim sales dan operasi kami untuk mendiskusikan kebutuhan pasokan gas LPG (HARIGAS), gas CNG industri, atau transportasi migas & batu bara.' }}
                </p>
            </div>

            <!-- 3 Kolom Kartu Kontak -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                <!-- Alamat Kantor Pusat -->
                <div class="p-6 sm:p-8 rounded-2xl sm:rounded-3xl bg-slate-50 border border-slate-200 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-brand-600 text-white flex items-center justify-center text-xl shadow-md shadow-brand-600/20">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        <h3 class="font-bold text-base sm:text-lg text-navy-900">Kantor Pusat</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            {{ $settings['company_address'] ?? '18 Office Park 21st Floor, Jl. TB Simatupang Kav. 18, Pasar Minggu, Jakarta Selatan (12520)' }}
                        </p>
                        <div class="pt-2">
                            <span class="inline-block px-2.5 py-1 bg-white rounded-lg border border-slate-200 text-brand-700 font-mono text-[11px] font-bold">
                                NIB: {{ $settings['company_nib'] ?? '2211210015706' }}
                            </span>
                        </div>
                    </div>
                    <div class="pt-5 sm:pt-6 border-t border-slate-200 mt-5 sm:mt-6 text-xs text-slate-500 font-medium flex items-center gap-2">
                        <i class="fa-solid fa-clock text-brand-600"></i>
                        <span>{{ $settings['operational_hours'] ?? 'Senin - Sabtu: 08.00 - 17.00 WIB' }}</span>
                    </div>
                </div>

                <!-- Hotline WhatsApp & Telepon -->
                <div class="p-6 sm:p-8 rounded-2xl sm:rounded-3xl bg-emerald-50/70 border border-emerald-200 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-xl shadow-md shadow-emerald-600/20">
                            <i class="fa-brands fa-whatsapp"></i>
                        </div>
                        <h3 class="font-bold text-base sm:text-lg text-navy-900">Hotline &amp; Telepon</h3>
                        <div class="space-y-3 text-xs text-slate-600">
                            <div>
                                <span class="text-slate-400 font-semibold block text-[11px] uppercase">WhatsApp Hotline</span>
                                <strong class="text-emerald-700 text-base font-extrabold block">{{ $settings['company_whatsapp'] ?? '+62 811 8888 1234' }}</strong>
                            </div>
                            <div>
                                <span class="text-slate-400 font-semibold block text-[11px] uppercase">Telepon Kantor</span>
                                <strong class="text-navy-900 text-sm font-bold block">{{ $settings['company_phone'] ?? '+62 21 2278 1818' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="pt-5 sm:pt-6 border-t border-emerald-200 mt-5 sm:mt-6">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['company_whatsapp'] ?? '6281188881234') }}?text=Halo%20PT.%20Erickman%20Sarana%20Abadi,%20saya%20tertarik%20dengan%20layanan%20transportasi%20dan%20armada%20Anda." target="_blank" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-600/20 transition transform hover:-translate-y-0.5">
                            <i class="fa-brands fa-whatsapp text-sm"></i>
                            <span>Chat WhatsApp Sekarang</span>
                        </a>
                    </div>
                </div>

                <!-- Korespondensi Email -->
                <div class="p-6 sm:p-8 rounded-2xl sm:rounded-3xl bg-sky-50/70 border border-sky-200 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-sky-600 text-white flex items-center justify-center text-xl shadow-md shadow-sky-600/20">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <h3 class="font-bold text-base sm:text-lg text-navy-900">Korespondensi Email</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Kirimkan surat resmi, permintaan penawaran harga, atau korespondensi bisnis ke email resmi kami:
                        </p>
                        <p class="text-xs sm:text-sm font-bold text-sky-800 font-mono bg-white p-2.5 rounded-xl border border-sky-200 text-center break-all sm:break-normal">
                            {{ $settings['company_email'] ?? 'info@erickman.co.id' }}
                        </p>
                    </div>
                    <div class="pt-5 sm:pt-6 border-t border-sky-200 mt-5 sm:mt-6">
                        <a href="mailto:{{ $settings['company_email'] ?? 'info@erickman.co.id' }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs shadow-md shadow-sky-600/20 transition transform hover:-translate-y-0.5">
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                            <span>Kirim Email Resmi</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- FORMULIR PERMINTAAN PENAWARAN (RFQ) - DIKONTROL DARI ADMIN PANEL (CMS TOGGLE) -->
            @if(($settings['show_rfq_form'] ?? '0') == '1')
            <div class="mt-12 sm:mt-16 bg-slate-50 p-5 sm:p-10 rounded-2xl sm:rounded-3xl border border-slate-200 shadow-lg shadow-slate-100">
                <h3 class="font-bold text-lg sm:text-xl text-navy-900 mb-1 sm:mb-2">Formulir Permintaan Penawaran (RFQ)</h3>
                <p class="text-xs text-slate-500 mb-5 sm:mb-6">Silakan lengkapi rincian kebutuhan Anda di bawah ini, pesan akan langsung masuk ke Admin Panel kami.</p>

                <form action="{{ route('inquiry.submit') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap *</label>
                            <input type="text" name="name" required placeholder="Contoh: Budi Santoso" class="w-full px-3.5 sm:px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Perusahaan / Instansi</label>
                            <input type="text" name="company" placeholder="Contoh: PT Manufaktur Jaya" class="w-full px-3.5 sm:px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm bg-white">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Alamat Email *</label>
                            <input type="email" name="email" required placeholder="nama@perusahaan.co.id" class="w-full px-3.5 sm:px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">No. WhatsApp / HP</label>
                            <input type="text" name="phone" placeholder="0812xxxxxxx" class="w-full px-3.5 sm:px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm bg-white">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Layanan yang Diminati</label>
                            <select name="service_interest" class="w-full px-3.5 sm:px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm bg-white">
                                <option value="">-- Pilih Layanan --</option>
                                @foreach($services as $s)
                                <option value="{{ $s->title }}">{{ $s->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Subjek Permintaan *</label>
                            <input type="text" name="subject" required placeholder="Contoh: Penawaran Pasokan Gas CNG" class="w-full px-3.5 sm:px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm bg-white">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Detail Kebutuhan / Pesan *</label>
                        <textarea name="message" rows="4" required placeholder="Tuliskan detail muatan, volume kubikasi gas, rute tujuan, durasi sewa, atau spesifikasi yang diinginkan..." class="w-full px-3.5 sm:px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm bg-white"></textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-3 sm:py-3.5 px-4 sm:px-6 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs sm:text-sm tracking-wide shadow-md shadow-brand-600/30 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <i class="fa-regular fa-paper-plane"></i>
                            <span>Kirim Permintaan Penawaran Sekarang</span>
                        </button>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </section>

@endsection
