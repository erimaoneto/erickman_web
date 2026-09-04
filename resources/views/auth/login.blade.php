<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin Panel - PT. Erickman Sarana Abadi</title>
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
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 group">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-brand-700 to-emerald-500 flex items-center justify-center text-white text-2xl shadow-lg shadow-emerald-500/20 flex-shrink-0">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <div class="text-left">
                    <span class="block font-black text-lg sm:text-xl tracking-tight text-navy-900 leading-tight">PT. ERICKMAN<br>SARANA ABADI<span class="text-brand-600">.</span></span>
                    <span class="block text-[11px] font-bold uppercase tracking-widest text-slate-500 mt-0.5">Admin Portal</span>
                </div>
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
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Admin</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-envelope text-sm"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email', 'admin@erickman.co.id') }}" required autofocus placeholder="admin@erickman.co.id" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-brand-600 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </div>
                        <input type="password" name="password" value="admin12345" required placeholder="••••••••" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-600 focus:border-brand-600 text-sm">
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
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <span>Masuk ke Admin Panel</span>
                    </button>
                </div>
            </form>

            <!-- Default Login Info Helper -->
            <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                <span class="text-[11px] font-semibold text-slate-400 block mb-1">Kredensial Default Login:</span>
                <div class="inline-block bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-600 font-mono">
                    admin@erickman.co.id / admin12345
                </div>
            </div>
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-xs font-semibold text-slate-500 hover:text-navy-900 transition flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Website Utama
            </a>
        </div>
    </div>

</body>
</html>
