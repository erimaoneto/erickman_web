<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin Panel - PT. Erickman Sarana Abadi</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('/images/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('/images/favicon.ico') }}">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        brand: { 600: '#059669', 700: '#047857' },
                        navy: { 900: '#0f172a', 950: '#020617' }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-slate-100 font-sans min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full">
        <!-- Brand Header -->
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex flex-col items-center gap-2 group">
                <img src="{{ asset('/images/logo-erickman.png') }}" alt="PT. Erickman Sarana Abadi" class="h-11 sm:h-12 w-auto object-contain group-hover:scale-105 transition">
                <div class="text-xs font-bold text-navy-900 tracking-wide mt-1">PT. ERICKMAN SARANA ABADI</div>
                <span class="block text-[10px] font-bold uppercase tracking-widest text-slate-500">Portal Operasional &amp; Keuangan</span>
            </a>
            <p class="text-xs text-slate-500 mt-2">Masuk untuk mengelola konten, armada, keuangan, dan email.</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-3xl p-8 sm:p-10 shadow-xl shadow-slate-200/70 border border-slate-200">
            @if(session('success'))
            <div class="mb-4 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-800 flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-4 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-xs font-semibold text-rose-800 flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                <!-- Honeypot for bot protection -->
                <div class="hidden" aria-hidden="true" style="display:none;">
                    <input type="text" name="_hp_security_check" tabindex="-1" autocomplete="off">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Admin</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-envelope text-sm"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@erickman.co.id" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-brand-600 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </div>
                        <input type="password" id="password" name="password" required placeholder="••••••••" class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-brand-600 text-sm">
                        <button type="button" onclick="togglePasswordVisibility()" aria-label="Tampilkan kata sandi" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none">
                            <i id="togglePasswordIcon" class="fa-solid fa-eye text-sm"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 text-xs font-medium text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-brand-600 focus:ring-brand-600">
                        <span>Ingat Saya</span>
                    </label>
                </div>

                <div class="pt-3">
                    <button type="submit" class="w-full py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm shadow-md shadow-brand-600/30 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>Masuk ke Admin Panel</span>
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-center gap-2 text-[11px] text-slate-400 font-medium">
                <i class="fa-solid fa-lock text-emerald-600"></i>
                <span>Koneksi Aman SSL 256-bit & Proteksi Anti Brute-Force</span>
            </div>
        </div>

        <script>
            function togglePasswordVisibility() {
                const passInput = document.getElementById('password');
                const passIcon = document.getElementById('togglePasswordIcon');
                if (passInput.type === 'password') {
                    passInput.type = 'text';
                    passIcon.classList.remove('fa-eye');
                    passIcon.classList.add('fa-eye-slash');
                } else {
                    passInput.type = 'password';
                    passIcon.classList.remove('fa-eye-slash');
                    passIcon.classList.add('fa-eye');
                }
            }
        </script>

        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-xs font-semibold text-slate-500 hover:text-navy-900 transition flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Website Utama
            </a>
        </div>
    </div>

</body>
</html>
