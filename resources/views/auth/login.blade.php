<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950 text-slate-100 dark">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login System - TravelManager Executive</title>

    <!-- Google Fonts & Material Symbols -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #020617;
            background-image: 
                radial-gradient(at 0% 0%, rgba(2, 132, 199, 0.18) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(245, 158, 11, 0.12) 0px, transparent 50%),
                radial-gradient(at 50% 30%, rgba(99, 102, 241, 0.15) 0px, transparent 70%);
            background-attachment: fixed;
        }

        .glass-portal {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.7), 0 0 30px rgba(56, 189, 248, 0.1);
        }

        .swal2-popup {
            background-color: #0b1329 !important;
            color: #f8fafc !important;
            border: 1px solid rgba(56, 189, 248, 0.2) !important;
            border-radius: 1.25rem !important;
        }
        .swal2-title {
            color: #ffffff !important;
            font-family: 'Outfit', sans-serif !important;
        }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4 font-sans selection:bg-sky-500 selection:text-white">

    <div class="w-full max-w-md space-y-6">
        
        <!-- App Header Brand Logo -->
        <div class="text-center space-y-3">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-sky-500 via-indigo-500 to-amber-500 p-0.5 mx-auto shadow-2xl shadow-sky-500/30 transition-transform hover:scale-105 duration-300">
                <div class="w-full h-full bg-slate-950 rounded-[14px] flex items-center justify-center text-sky-400">
                    <span class="material-symbols-outlined text-3xl">directions_bus</span>
                </div>
            </div>
            <div>
                <h1 class="font-display font-black text-3xl text-white tracking-wide">TravelManager</h1>
                <p class="text-xs text-sky-300/80 font-medium mt-1">Sistem Manajemen Fleet & Operasional Travel</p>
            </div>
        </div>

        <!-- Login Glass Card Portal -->
        <div class="glass-portal p-8 rounded-3xl space-y-6">

            <div class="space-y-1">
                <h2 class="font-display font-bold text-xl text-white">Masuk ke Akun Anda</h2>
                <p class="text-xs text-slate-400">Masukkan Email (Owner) atau Nomor HP (Supir) beserta kata sandi Anda.</p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4 text-xs">
                @csrf

                <div>
                    <label class="block font-semibold text-slate-300 mb-1.5">
                        Email (Owner) / Nomor HP (Supir)
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3.5 top-3 text-slate-500 text-base">account_circle</span>
                        <input type="text" id="login" name="login" value="{{ old('login') }}" required placeholder="owner@travel.com atau 081298765432"
                               class="w-full pl-10 pr-4 py-3 rounded-2xl bg-slate-900/90 border border-slate-700/80 text-white placeholder-slate-500 focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-all">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-300 mb-1.5">Kata Sandi (Password)</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3.5 top-3 text-slate-500 text-base">lock</span>
                        <input type="password" id="password" name="password" required placeholder="••••••••"
                               class="w-full pl-10 pr-10 py-3 rounded-2xl bg-slate-900/90 border border-slate-700/80 text-white placeholder-slate-500 focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-all">
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute right-3.5 top-3 text-slate-500 hover:text-slate-300 focus:outline-none">
                            <span id="pass-icon" class="material-symbols-outlined text-base">visibility</span>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-[11px] pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-400 hover:text-slate-200">
                        <input type="checkbox" name="remember" class="rounded bg-slate-900 border-slate-700 text-sky-600 focus:ring-0">
                        Ingat Saya di Perangkat Ini
                    </label>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white font-bold text-xs shadow-lg shadow-sky-600/30 transition-all flex items-center justify-center gap-2 group">
                    <span class="material-symbols-outlined text-base group-hover:translate-x-0.5 transition-transform">login</span>
                    Masuk ke Sistem Travel
                </button>
            </form>

            <!-- Support Notice -->
            <div class="p-3.5 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-300 text-[11px] space-y-1">
                <div class="flex items-center gap-1.5 font-bold text-amber-400">
                    <span class="material-symbols-outlined text-sm">info</span>
                    Informasi Akun Supir
                </div>
                <p class="text-slate-300 leading-relaxed">
                    Pengemudi/Supir masuk menggunakan <strong>Nomor HP Terdaftar</strong>. Apabila terjadi kesalahan login atau lupa kata sandi, hubungi Management Travel.
                </p>
            </div>
        </div>

        <p class="text-center text-[11px] text-slate-500 font-medium">
            &copy; {{ date('Y') }} TravelManager System. All rights reserved.
        </p>

    </div>

    <script>
        function togglePasswordVisibility() {
            const passInput = document.getElementById('password');
            const passIcon = document.getElementById('pass-icon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                passIcon.textContent = 'visibility_off';
            } else {
                passInput.type = 'password';
                passIcon.textContent = 'visibility';
            }
        }

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Gagal Login!',
                text: "{{ $errors->first() }}",
            });
        @endif
    </script>
</body>
</html>
