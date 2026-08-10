<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login System - TravelManager</title>

    <!-- Google Fonts & Material Symbols -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .glass-panel {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-900 via-slate-950 to-black font-sans">

    <div class="w-full max-w-md space-y-6">
        
        <!-- App Header Logo -->
        <div class="text-center space-y-2">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-sky-500 to-indigo-600 flex items-center justify-center text-white mx-auto shadow-xl shadow-sky-500/20">
                <span class="material-symbols-outlined text-3xl">directions_bus</span>
            </div>
            <h1 class="font-display font-extrabold text-2xl text-white tracking-wide">TravelManager</h1>
            <p class="text-xs text-slate-400">Sistem Manajemen Operasional & Keuangan Travel</p>
        </div>

        <!-- Login Card -->
        <div class="glass-panel p-8 rounded-3xl shadow-2xl space-y-6">
            <div>
                <h2 class="font-display font-bold text-lg text-white">Masuk ke Akun Anda</h2>
                <p class="text-xs text-slate-400 mt-1">Masukkan Email (Owner) atau Nomor HP (Supir) beserta kata sandi Anda.</p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4 text-xs">
                @csrf

                <div>
                    <label class="block font-semibold text-slate-300 mb-1">
                        Email (Owner) / Nomor HP (Supir)
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-500 text-sm">account_circle</span>
                        <input type="text" id="login" name="login" value="{{ old('login') }}" required placeholder="owner@travel.com atau 081298765432"
                               class="w-full pl-9 pr-3 py-2.5 rounded-xl bg-slate-900/90 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-300 mb-1">Kata Sandi (Password)</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-500 text-sm">lock</span>
                        <input type="password" id="password" name="password" required placeholder="••••••••"
                               class="w-full pl-9 pr-3 py-2.5 rounded-xl bg-slate-900/90 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                    </div>
                </div>

                <div class="flex items-center justify-between text-[11px] pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-400 hover:text-slate-200">
                        <input type="checkbox" name="remember" class="rounded bg-slate-900 border-slate-700 text-sky-600 focus:ring-0">
                        Ingat Saya
                    </label>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs shadow-lg shadow-sky-600/30 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">login</span>
                    Masuk ke System
                </button>
            </form>

            <!-- Lupa Password & Support Notice for Supir -->
            <div class="p-3.5 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-300 text-[11px] space-y-1">
                <div class="flex items-center gap-1.5 font-bold text-amber-400">
                    <span class="material-symbols-outlined text-sm">info</span>
                    Informasi Lupa Password & Kendala Login
                </div>
                <p class="text-slate-300 leading-relaxed">
                    Apabila terjadi kesalahan login, lupa nomor HP, atau lupa kata sandi Supir, silakan <strong>menghubungi Owner / Management Travel</strong> untuk bantuan kata sandi atau reset akun.
                </p>
            </div>
        </div>

        <p class="text-center text-[11px] text-slate-500">
            &copy; {{ date('Y') }} TravelManager System. All rights reserved.
        </p>

    </div>

    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                background: '#0f172a',
                color: '#f8fafc'
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Gagal Login!',
                text: "{{ $errors->first() }}",
                background: '#0f172a',
                color: '#f8fafc'
            });
        @endif
    </script>
</body>
</html>
