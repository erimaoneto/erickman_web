<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - PT. Erickman Sarana Abadi</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        brand: {
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
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
    
    <!-- FontAwesome & Chart.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-100 font-sans text-slate-800 antialiased" x-data="{ sidebarOpen: false }">

    <div class="min-h-screen flex">
        <!-- Sidebar Backdrop for Mobile -->
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-sm lg:hidden"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" 
               class="fixed lg:static inset-y-0 left-0 z-50 w-72 bg-navy-950 text-slate-300 flex flex-col justify-between transition-transform duration-300 ease-in-out border-r border-slate-800 shadow-xl lg:shadow-none">
            
            <div class="p-6 overflow-y-auto">
                <!-- Brand Header -->
                <div class="flex items-center justify-between pb-6 border-b border-slate-800">
                    <a href="{{ Auth::user()->role === 'keuangan' ? route('admin.finance.index') : route('admin.dashboard') }}" class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-700 to-emerald-500 flex items-center justify-center text-white text-xl font-bold flex-shrink-0">
                            <i class="fa-solid fa-truck-fast"></i>
                        </div>
                        <div>
                            <span class="block font-black text-sm tracking-tight text-white leading-snug">PT. ERICKMAN<br>SARANA ABADI<span class="text-brand-500">.</span></span>
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-0.5">
                                {{ Auth::user()->role === 'keuangan' ? 'Portal Keuangan' : 'Admin Panel' }}
                            </span>
                        </div>
                    </a>
                    <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <!-- Navigation Links -->
                <nav class="mt-6 space-y-6">
                    @if(Auth::user()->role === 'superadmin')
                    <!-- Dashboard -->
                    <div>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                            <i class="fa-solid fa-gauge-high w-5 text-center"></i>
                            <span>Dashboard Utama</span>
                        </a>
                    </div>

                    <!-- Fleet Dashboard -->
                    <div class="space-y-1">
                        <div class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">Dashboard Armada</div>
                        <a href="{{ route('admin.fleets.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.fleets.index', 'admin.fleets.show', 'admin.fleets.edit') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                            <i class="fa-solid fa-truck-moving w-5 text-center"></i>
                            <span>Kelola Data Armada</span>
                        </a>
                        <a href="{{ route('admin.fleets.create') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.fleets.create') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                            <i class="fa-solid fa-plus w-5 text-center text-xs"></i>
                            <span>Tambah Unit Truk</span>
                        </a>
                    </div>
                    @endif

                    <!-- Finance Dashboard (Accessible by superadmin & keuangan) -->
                    <div class="space-y-1">
                        <div class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                            {{ Auth::user()->role === 'keuangan' ? 'Menu Keuangan' : 'Dashboard Keuangan' }}
                        </div>
                        <a href="{{ route('admin.finance.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.finance.index', 'admin.finance.edit') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                            <i class="fa-solid fa-wallet w-5 text-center"></i>
                            <span>Arus Kas & Transaksi</span>
                        </a>
                        <a href="{{ route('admin.finance.create') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.finance.create') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                            <i class="fa-solid fa-file-invoice-dollar w-5 text-center text-xs"></i>
                            <span>Catat Transaksi</span>
                        </a>
                        <a href="{{ route('admin.finance.report') }}" target="_blank" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.finance.report') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                            <i class="fa-solid fa-print w-5 text-center text-xs"></i>
                            <span>Laporan Cetak</span>
                        </a>
                    </div>

                    @if(Auth::user()->role === 'superadmin')
                    <!-- Email Module -->
                    <div class="space-y-1">
                        <div class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">Modul Email</div>
                        <a href="{{ route('admin.email.inbox') }}" class="flex items-center justify-between px-3.5 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.email.inbox', 'admin.email.show') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-inbox w-5 text-center"></i>
                                <span>Pesan Masuk</span>
                            </div>
                            @php
                                $unread = \App\Models\Inquiry::where('is_read', false)->count();
                            @endphp
                            @if($unread > 0)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500 text-white">{{ $unread }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.email.compose') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.email.compose') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                            <i class="fa-solid fa-paper-plane w-5 text-center"></i>
                            <span>Kirim Email (SMTP)</span>
                        </a>
                        <a href="{{ route('admin.email.outbox') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.email.outbox') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                            <i class="fa-solid fa-clock-rotate-left w-5 text-center"></i>
                            <span>Riwayat Terkirim</span>
                        </a>
                    </div>

                    <!-- CMS Web Content -->
                    <div class="space-y-1">
                        <div class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">CMS Konten Website</div>
                        <a href="{{ route('admin.content.settings') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.content.settings') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                            <i class="fa-solid fa-sliders w-5 text-center"></i>
                            <span>Profil & Legalitas (NIB)</span>
                        </a>
                        <a href="{{ route('admin.content.banners') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.content.banners') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                            <i class="fa-solid fa-images w-5 text-center"></i>
                            <span>Banner Slider Foto</span>
                        </a>
                        <a href="{{ route('admin.content.services') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.content.services*') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                            <i class="fa-solid fa-briefcase w-5 text-center"></i>
                            <span>Layanan & KBLI</span>
                        </a>
                    </div>

                    <!-- User Management -->
                    <div class="space-y-1">
                        <div class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">Pengaturan Sistem</div>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.users.*') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                            <i class="fa-solid fa-users-gear w-5 text-center"></i>
                            <span>Manajemen Pengguna</span>
                        </a>
                    </div>
                    @endif
                </nav>
            </div>

            <!-- Footer & Logout -->
            <div class="p-6 border-t border-slate-800 space-y-3">
                <a href="{{ route('home') }}" target="_blank" class="w-full flex items-center justify-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-300 bg-slate-900 hover:bg-slate-800 transition border border-slate-700">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    <span>Lihat Website Depan</span>
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold text-rose-400 bg-rose-500/10 hover:bg-rose-500 hover:text-white transition">
                        <i class="fa-solid fa-power-off"></i>
                        <span>Keluar (Logout)</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Topbar -->
            <header class="h-16 bg-white border-b border-slate-200 px-4 sm:px-8 flex items-center justify-between sticky top-0 z-30 shadow-sm">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-slate-600 hover:bg-slate-100 lg:hidden">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                    <h1 class="text-base sm:text-lg font-bold text-navy-900">@yield('page_title', 'Dashboard')</h1>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <div class="flex items-center justify-end gap-2">
                            <span class="block text-xs font-bold text-navy-900">{{ Auth::user()->name ?? 'Administrator' }}</span>
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider {{ Auth::user()->role === 'keuangan' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ Auth::user()->role === 'keuangan' ? 'Keuangan' : 'Admin' }}
                            </span>
                        </div>
                        <span class="block text-[10px] text-slate-500">{{ Auth::user()->email ?? 'admin@erickman.co.id' }}</span>
                    </div>
                    <div class="w-9 h-9 rounded-full {{ Auth::user()->role === 'keuangan' ? 'bg-amber-600' : 'bg-brand-600' }} text-white font-bold flex items-center justify-center text-xs shadow-sm">
                        <i class="fa-solid {{ Auth::user()->role === 'keuangan' ? 'fa-wallet' : 'fa-user-shield' }}"></i>
                    </div>
                </div>
            </header>

            <!-- Page Body -->
            <main class="flex-1 p-4 sm:p-8 max-w-7xl w-full mx-auto space-y-6">
                <!-- Alerts / Flash Messages -->
                @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3 shadow-sm">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                @if(session('warning'))
                <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm flex items-center gap-3 shadow-sm">
                    <i class="fa-solid fa-triangle-exclamation text-amber-600 text-lg"></i>
                    <span>{{ session('warning') }}</span>
                </div>
                @endif

                @if($errors->any())
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm space-y-1 shadow-sm">
                    <div class="font-bold flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                        <span>Mohon periksa kesalahan input berikut:</span>
                    </div>
                    <ul class="list-disc pl-5 text-xs">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
