<!DOCTYPE html>
<html lang="id" class="scroll-smooth overflow-x-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PT. Erickman Sarana Abadi - Transportasi Gas Alam & Logistik Khusus')</title>
    <meta name="description" content="@yield('meta_description', 'PT. Erickman Sarana Abadi - Solusi Transportasi Gas Alam (CNG/LNG), Angkutan Barang Khusus & Sewa Armada Truk Terpercaya di Indonesia.')">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('/images/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('/images/favicon.ico') }}">
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN with custom config -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        },
                        navy: {
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#020617',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans text-slate-800 bg-slate-50 antialiased selection:bg-brand-600 selection:text-white overflow-x-hidden" x-data="{ mobileMenu: false }">

    <!-- Topbar Info -->
    <div class="bg-navy-950 text-slate-300 text-xs py-2 border-b border-slate-800">
        <div class="max-w-7xl xl:max-w-[1360px] 2xl:max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-10 flex flex-col md:flex-row justify-between items-center gap-2">
            <div class="flex items-center gap-4 flex-wrap justify-center md:justify-start">
                <span><i class="fa-solid fa-certificate text-brand-500 mr-1.5"></i> NIB Resmi: <strong class="text-white">{{ $settings['company_nib'] ?? '2211210015706' }}</strong></span>
                <span class="hidden sm:inline text-slate-600">|</span>
                <span><i class="fa-solid fa-building text-brand-500 mr-1.5"></i> 18 Office Park TB Simatupang, Jakarta Selatan</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="mailto:{{ $settings['company_email'] ?? 'info@erickman.co.id' }}" class="hover:text-brand-400 transition flex items-center gap-1.5">
                    <i class="fa-regular fa-envelope text-brand-500"></i> {{ $settings['company_email'] ?? 'info@erickman.co.id' }}
                </a>
                <span class="text-slate-600">|</span>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['company_whatsapp'] ?? '6281188881234') }}" target="_blank" class="hover:text-emerald-400 text-emerald-400 font-semibold transition flex items-center gap-1.5">
                    <i class="fa-brands fa-whatsapp"></i> Chat WhatsApp
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md shadow-sm border-b border-slate-100 transition duration-200">
        <div class="max-w-7xl xl:max-w-[1360px] 2xl:max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-10">
            <div class="flex justify-between items-center h-20 gap-4 lg:gap-8">
                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group flex-shrink-0">
                    <img src="{{ asset('/images/logo-erickman.png') }}" alt="{{ $settings['company_name'] ?? 'PT. Erickman Sarana Abadi' }}" class="h-9 sm:h-11 w-auto object-contain group-hover:scale-105 transition">
                    <div class="hidden sm:flex flex-col border-l border-slate-200 pl-3">
                        <span class="font-extrabold text-xs sm:text-sm tracking-tight text-navy-900 group-hover:text-brand-600 transition leading-tight whitespace-nowrap">
                            PT. ERICKMAN SARANA ABADI
                        </span>
                        <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-widest text-slate-500 mt-0.5">
                            Oil, Gas, &amp; Transportation
                        </span>
                    </div>
                </a>

                <!-- Desktop Menu -->
                <nav class="hidden lg:flex items-center gap-5 xl:gap-7 2xl:gap-8 text-[13px] xl:text-sm font-semibold text-slate-700 whitespace-nowrap">
                    @if(isset($navMenus) && $navMenus->count() > 0)
                        @foreach($navMenus as $navItem)
                            <a href="{{ Str::startsWith($navItem->url, '#') ? route('home') . $navItem->url : $navItem->url }}" class="hover:text-brand-600 transition py-2">
                                {{ $navItem->title }}
                            </a>
                        @endforeach
                    @else
                        <a href="{{ route('home') }}#beranda" class="hover:text-brand-600 transition py-2">Beranda</a>
                        <a href="{{ route('home') }}#tentang" class="hover:text-brand-600 transition py-2">Tentang Kami</a>
                        <a href="{{ route('home') }}#layanan" class="hover:text-brand-600 transition py-2">Layanan &amp; KBLI</a>
                        <a href="{{ route('home') }}#armada" class="hover:text-brand-600 transition py-2">Armada Kami</a>
                        <a href="{{ route('home') }}#rekanan" class="hover:text-brand-600 transition py-2">Rekanan Kami</a>
                        <a href="{{ route('home') }}#keunggulan" class="hover:text-brand-600 transition py-2">Keunggulan HSE</a>
                        <a href="{{ route('home') }}#kontak" class="hover:text-brand-600 transition py-2">Kontak</a>
                    @endif
                </nav>

                <!-- Admin Portal Login Icon -->
                <div class="hidden lg:flex items-center flex-shrink-0">
                    <a href="{{ route('login') }}" class="p-2.5 rounded-xl text-slate-500 hover:text-navy-900 hover:bg-slate-100 transition" title="Portal Admin Erickman">
                        <i class="fa-solid fa-lock text-base"></i>
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex items-center lg:hidden gap-2">
                    <a href="{{ route('login') }}" class="p-2 rounded text-slate-600 hover:bg-slate-100 text-sm">
                        <i class="fa-solid fa-lock"></i>
                    </a>
                    <button @click="mobileMenu = !mobileMenu" type="button" class="p-2 rounded-lg text-slate-700 hover:bg-slate-100 focus:outline-none">
                        <i :class="mobileMenu ? 'fa-solid fa-xmark' : 'fa-solid fa-bars'" class="text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenu" x-cloak class="lg:hidden border-t border-slate-100 bg-white px-4 pt-3 pb-6 space-y-2 shadow-xl">
            @if(isset($navMenus) && $navMenus->count() > 0)
                @foreach($navMenus as $navItem)
                    <a @click="mobileMenu = false" href="{{ Str::startsWith($navItem->url, '#') ? route('home') . $navItem->url : $navItem->url }}" class="block px-3 py-2 rounded-md font-medium text-slate-700 hover:bg-slate-50">
                        {{ $navItem->title }}
                    </a>
                @endforeach
            @else
                <a @click="mobileMenu = false" href="{{ route('home') }}#beranda" class="block px-3 py-2 rounded-md font-medium text-slate-700 hover:bg-slate-50">Beranda</a>
                <a @click="mobileMenu = false" href="{{ route('home') }}#tentang" class="block px-3 py-2 rounded-md font-medium text-slate-700 hover:bg-slate-50">Tentang Kami</a>
                <a @click="mobileMenu = false" href="{{ route('home') }}#layanan" class="block px-3 py-2 rounded-md font-medium text-slate-700 hover:bg-slate-50">Layanan &amp; KBLI</a>
                <a @click="mobileMenu = false" href="{{ route('home') }}#armada" class="block px-3 py-2 rounded-md font-medium text-slate-700 hover:bg-slate-50">Armada Kami</a>
                <a @click="mobileMenu = false" href="{{ route('home') }}#rekanan" class="block px-3 py-2 rounded-md font-medium text-slate-700 hover:bg-slate-50">Rekanan Kami</a>
                <a @click="mobileMenu = false" href="{{ route('home') }}#keunggulan" class="block px-3 py-2 rounded-md font-medium text-slate-700 hover:bg-slate-50">Keunggulan HSE</a>
                <a @click="mobileMenu = false" href="{{ route('home') }}#kontak" class="block px-3 py-2 rounded-md font-medium text-slate-700 hover:bg-slate-50">Kontak</a>
            @endif
        </div>
    </header>

    <!-- Content Slot -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-navy-950 text-slate-300 pt-16 pb-8 border-t border-slate-800">
        <div class="max-w-7xl xl:max-w-[1360px] 2xl:max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 pb-12 border-b border-slate-800">
                <!-- Company Profile -->
                <div class="space-y-4">
                    <div class="space-y-2">
                        <img src="{{ asset('/images/logo-erickman-white.png') }}" alt="{{ $settings['company_name'] ?? 'PT. Erickman Sarana Abadi' }}" class="h-9 w-auto object-contain brightness-110">
                        <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">Oil, Gas, &amp; Transportation</span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        {{ $settings['company_description'] ?? 'Penyedia terpercaya untuk pengadaan dan transportasi gas alam (CNG/LNG), angkutan khusus berisiko tinggi, serta penyewaan armada truk berstandar keselamatan prima.' }}
                    </p>
                    <div class="text-xs text-slate-400 pt-1">
                        <span class="inline-block px-2.5 py-1 bg-slate-800 rounded border border-slate-700 text-brand-400 font-mono">
                            NIB: {{ $settings['company_nib'] ?? '2211210015706' }}
                        </span>
                    </div>
                </div>

                <!-- Fast Links -->
                <div>
                    <h4 class="text-white font-bold text-base mb-4 tracking-wide uppercase text-xs text-brand-400">Navigasi Utama</h4>
                    <ul class="space-y-2.5 text-sm text-slate-400">
                        <li><a href="{{ route('home') }}#beranda" class="hover:text-white transition">Beranda</a></li>
                        <li><a href="{{ route('home') }}#tentang" class="hover:text-white transition">Profil Perusahaan & NIB</a></li>
                        <li><a href="{{ route('home') }}#layanan" class="hover:text-white transition">Daftar Layanan Industri</a></li>
                        <li><a href="{{ route('home') }}#armada" class="hover:text-white transition">Spesifikasi Armada Truk</a></li>
                        <li><a href="{{ route('home') }}#rekanan" class="hover:text-white transition">Rekanan &amp; Mitra Kerja</a></li>
                        <li><a href="{{ route('home') }}#kontak" class="hover:text-white transition">Kontak &amp; Kantor Operasional</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-brand-400 transition text-xs flex items-center gap-1.5"><i class="fa-solid fa-lock text-[10px]"></i> Login Admin Panel</a></li>
                    </ul>
                </div>

                <!-- Legalitas & KBLI -->
                <div>
                    <h4 class="text-white font-bold text-base mb-4 tracking-wide uppercase text-xs text-brand-400">Legalitas & KBLI Resmi</h4>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-check text-brand-500 mt-0.5"></i>
                            <span><strong>35201 & 35202:</strong> Pengadaan & Distribusi Gas Alam</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-check text-brand-500 mt-0.5"></i>
                            <span><strong>49432:</strong> Angkutan Bermotor Barang Khusus (Gas/B3)</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-check text-brand-500 mt-0.5"></i>
                            <span><strong>77100:</strong> Penyewaan Mobil, Bus, Truk</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-check text-brand-500 mt-0.5"></i>
                            <span><strong>49431:</strong> Angkutan Bermotor Barang Umum</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-check text-brand-500 mt-0.5"></i>
                            <span><strong>46610:</strong> Perdagangan Besar BBM, Cair & Gas</span>
                        </li>
                    </ul>
                </div>

                <!-- Address & Contact -->
                <div>
                    <h4 class="text-white font-bold text-base mb-4 tracking-wide uppercase text-xs text-brand-400">Kantor Pusat</h4>
                    <div class="space-y-3 text-sm text-slate-400">
                        <div class="flex items-start gap-3">
                            <i class="fa-solid fa-location-dot text-brand-500 mt-1"></i>
                            <span>{{ $settings['company_address'] ?? '18 Office Park 21st Floor, Jl. TB Simatupang Kav. 18, Pasar Minggu, Jakarta Selatan (12520)' }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-phone text-brand-500"></i>
                            <span>{{ $settings['company_phone'] ?? '+62 21 2278 1818' }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-envelope text-brand-500"></i>
                            <span>{{ $settings['company_email'] ?? 'info@erickman.co.id' }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fa-brands fa-whatsapp text-emerald-400"></i>
                            <span>{{ $settings['company_whatsapp'] ?? '+62 811 8888 1234' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-8 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} {{ $settings['company_name'] ?? 'PT. Erickman Sarana Abadi' }} (erickman.co.id). Seluruh Hak Cipta Dilindungi.</p>
                <p class="flex items-center gap-3">
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-shield-halved text-brand-500"></i> Standar Keselamatan K3 & HSE</span>
                    <span>&bull;</span>
                    <span>Hostinger hPanel Ready</span>
                </p>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp Button -->
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['company_whatsapp'] ?? '6281188881234') }}?text=Halo%20PT.%20Erickman%20Sarana%20Abadi,%20saya%20tertarik%20dengan%20layanan%20transportasi%20dan%20armada%20Anda." target="_blank" class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-40 bg-emerald-500 hover:bg-emerald-600 text-white w-12 h-12 sm:w-14 sm:h-14 rounded-full flex items-center justify-center shadow-lg shadow-emerald-500/30 hover:scale-110 transition transform" title="Hubungi Kami via WhatsApp">
        <i class="fa-brands fa-whatsapp text-2xl sm:text-3xl"></i>
    </a>

</body>
</html>
