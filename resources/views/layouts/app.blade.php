<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900 text-slate-100">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'TravelManager') - System Management Travel</title>

    <!-- Google Fonts & Material Symbols -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f7ff',
                            100: '#e0effe',
                            500: '#0284c7',
                            600: '#0369a1',
                            700: '#075985',
                            900: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Html2Canvas CDN for Image Export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <style>
        .glass-panel {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glass-card {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.8) 0%, rgba(15, 23, 42, 0.9) 100%);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        /* Custom SweetAlert Dark Styling */
        .swal2-popup {
            background-color: #0f172a !important;
            color: #f8fafc !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 1rem !important;
        }
        .swal2-title {
            color: #ffffff !important;
            font-family: 'Outfit', sans-serif !important;
        }
        .swal2-html-container {
            color: #94a3b8 !important;
        }

        /* Print PDF Styles Optimization */
        @media print {
            aside, header, .no-print, button, form select, form input[type="date"] {
                display: none !important;
            }
            body {
                background-color: #ffffff !important;
                color: #0f172a !important;
                font-size: 11pt !important;
            }
            .glass-panel, .glass-card {
                background: #ffffff !important;
                color: #0f172a !important;
                border: 1px solid #cbd5e1 !important;
                box-shadow: none !important;
            }
            h1, h2, h3, h4, th, td, span, div, p {
                color: #0f172a !important;
            }
            table {
                width: 100% !important;
                border-collapse: collapse !important;
            }
            th, td {
                border-bottom: 1px solid #e2e8f0 !important;
                padding: 8px 12px !important;
            }
            .print-header {
                display: block !important;
                margin-bottom: 20px;
                border-bottom: 2px solid #0f172a;
                padding-bottom: 10px;
            }
        }
        .print-header {
            display: none;
        }
    </style>
</head>
<body class="h-full flex font-sans antialiased text-slate-200 bg-slate-950">

    <!-- Sidebar -->
    <aside class="w-64 glass-panel border-r border-slate-800 flex flex-col justify-between hidden md:flex shrink-0 no-print">
        <div>
            <!-- App Brand Header -->
            <div class="p-6 border-b border-slate-800/60 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-sky-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-sky-500/20">
                    <span class="material-symbols-outlined text-2xl">directions_bus</span>
                </div>
                <div>
                    <h1 class="font-display font-bold text-lg text-white tracking-wide">TravelManager</h1>
                    <p class="text-xs text-slate-400 font-medium">Fleet & Operations</p>
                </div>
            </div>

            <!-- Navigation Links strictly partitioned by Role -->
            <nav class="p-4 space-y-1.5">
                <!-- 1. Dashboard (Both) -->
                <a href="{{ url('/') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all {{ request()->is('/') || request()->is('dashboard') ? 'bg-sky-600/20 text-sky-400 border border-sky-500/30' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                    <span class="material-symbols-outlined text-[20px]">dashboard</span>
                    Dashboard
                </a>

                @if(auth()->check() && auth()->user()->isOwner())
                <!-- 2. Data Kendaraan (Owner Only) -->
                <a href="{{ route('vehicles.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all {{ request()->is('vehicles*') ? 'bg-sky-600/20 text-sky-400 border border-sky-500/30' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                    <span class="material-symbols-outlined text-[20px]">directions_car</span>
                    Data Kendaraan
                </a>

                <!-- 3. Data Sopir (Owner Only) -->
                <a href="{{ route('drivers.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all {{ request()->is('drivers*') ? 'bg-sky-600/20 text-sky-400 border border-sky-500/30' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                    <span class="material-symbols-outlined text-[20px]">badge</span>
                    Data Sopir
                </a>
                @endif

                <!-- 4. Booking Travel (Both) -->
                <a href="{{ route('bookings.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all {{ request()->is('bookings*') ? 'bg-sky-600/20 text-sky-400 border border-sky-500/30' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                    <span class="material-symbols-outlined text-[20px]">confirmation_number</span>
                    Booking Travel
                </a>

                <!-- 5. Jadwal Perjalanan (Both) -->
                <a href="{{ route('schedules.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all {{ request()->is('schedules*') ? 'bg-sky-600/20 text-sky-400 border border-sky-500/30' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                    <span class="material-symbols-outlined text-[20px]">calendar_today</span>
                    Jadwal Perjalanan
                </a>

                @if(auth()->check() && auth()->user()->isOwner())
                <!-- 6. Perawatan Kendaraan (Owner Only) -->
                <a href="{{ route('maintenances.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all {{ request()->is('maintenances*') ? 'bg-sky-600/20 text-sky-400 border border-sky-500/30' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                    <span class="material-symbols-outlined text-[20px]">build</span>
                    Perawatan Kendaraan
                </a>

                <!-- 7. Pengeluaran (Owner Only) -->
                <a href="{{ route('expenses.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all {{ request()->is('expenses*') ? 'bg-sky-600/20 text-sky-400 border border-sky-500/30' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                    <span class="material-symbols-outlined text-[20px]">payments</span>
                    Pengeluaran
                </a>

                <!-- 8. Laporan Keuangan (Owner Only) -->
                <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all {{ request()->is('reports*') ? 'bg-sky-600/20 text-sky-400 border border-sky-500/30' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                    <span class="material-symbols-outlined text-[20px]">analytics</span>
                    Laporan Keuangan
                </a>
                @endif
            </nav>
        </div>

        <!-- Footer Profile & Logout (Clickable Profile Icon Area) -->
        <div class="p-4 border-t border-slate-800/60 space-y-2">
            <div class="flex items-center justify-between px-3 py-2.5 rounded-xl bg-slate-900/60 border border-slate-800 hover:border-sky-500/30 transition-all">
                <a href="{{ route('profile.index') }}" title="Lihat Profil Saya" class="flex items-center gap-2.5 overflow-hidden group">
                    <div class="w-8 h-8 rounded-full {{ auth()->check() && auth()->user()->isOwner() ? 'bg-sky-600/30 text-sky-400 border border-sky-500/30' : 'bg-amber-600/30 text-amber-400 border border-amber-500/30' }} flex items-center justify-center font-bold text-xs shrink-0 group-hover:scale-105 transition-transform">
                        {{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 2)) : 'US' }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-semibold text-white truncate group-hover:text-sky-400 transition-colors">{{ auth()->check() ? auth()->user()->name : 'User' }}</p>
                        <span class="px-2 py-0.5 rounded text-[9px] font-bold tracking-wider inline-block {{ auth()->check() && auth()->user()->isOwner() ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20' }}">
                            {{ auth()->check() ? strtoupper(auth()->user()->role) : 'GUEST' }}
                        </span>
                    </div>
                </a>

                <div class="flex items-center gap-1">
                    <a href="{{ route('profile.index') }}" title="Profil Saya" class="p-1.5 rounded-lg bg-sky-500/10 hover:bg-sky-500/20 text-sky-400 border border-sky-500/20 transition-all flex items-center">
                        <span class="material-symbols-outlined text-sm">account_circle</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Keluar / Logout" class="p-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 transition-all flex items-center">
                            <span class="material-symbols-outlined text-sm">logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Container -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <!-- Top Header Bar -->
        <header class="h-16 glass-panel border-b border-slate-800 px-6 flex items-center justify-between shrink-0 no-print">
            <div class="flex items-center gap-3">
                <!-- Mobile Navigation Toggle -->
                <button onclick="document.getElementById('mobile-sidebar').classList.toggle('hidden')" class="md:hidden p-2 text-slate-400 hover:text-white rounded-lg bg-slate-800">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <h2 class="font-display font-semibold text-lg text-white">@yield('page_title', 'Dashboard')</h2>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('profile.index') }}" title="Lihat Profil Saya" class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-800/80 hover:bg-slate-700 text-slate-300 text-xs border border-slate-700 transition-all">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Role: <strong class="{{ auth()->check() && auth()->user()->isOwner() ? 'text-sky-400' : 'text-amber-400' }}">{{ auth()->check() ? strtoupper(auth()->user()->role) : 'GUEST' }}</strong></span>
                    <span class="material-symbols-outlined text-sm text-sky-400 ml-1">admin_panel_settings</span>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="sm:hidden">
                    @csrf
                    <button type="submit" class="p-2 rounded-lg bg-rose-500/10 text-rose-400 text-xs font-semibold flex items-center gap-1 border border-rose-500/20">
                        <span class="material-symbols-outlined text-sm">logout</span> Logout
                    </button>
                </form>

                <div class="text-xs text-slate-400 hidden lg:block">
                    {{ now()->format('d M Y') }}
                </div>
            </div>
        </header>

        <!-- Mobile Sidebar Drawer -->
        <div id="mobile-sidebar" class="hidden md:hidden fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md no-print">
            <div class="w-64 h-full bg-slate-900 border-r border-slate-800 p-4 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-sky-600 flex items-center justify-center text-white font-bold">
                                TM
                            </div>
                            <span class="font-bold text-white">TravelManager</span>
                        </div>
                        <button onclick="document.getElementById('mobile-sidebar').classList.add('hidden')" class="text-slate-400 hover:text-white">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>

                    <nav class="mt-4 space-y-1">
                        <a href="{{ url('/') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-slate-300 hover:bg-slate-800">Dashboard</a>
                        @if(auth()->check() && auth()->user()->isOwner())
                        <a href="{{ route('vehicles.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-slate-300 hover:bg-slate-800">Data Kendaraan</a>
                        <a href="{{ route('drivers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-slate-300 hover:bg-slate-800">Data Sopir</a>
                        @endif
                        <a href="{{ route('bookings.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-slate-300 hover:bg-slate-800">Booking Travel</a>
                        <a href="{{ route('schedules.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-slate-300 hover:bg-slate-800">Jadwal Perjalanan</a>
                        @if(auth()->check() && auth()->user()->isOwner())
                        <a href="{{ route('maintenances.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-slate-300 hover:bg-slate-800">Perawatan Kendaraan</a>
                        <a href="{{ route('expenses.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-slate-300 hover:bg-slate-800">Pengeluaran Operasional</a>
                        <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-slate-300 hover:bg-slate-800">Laporan Keuangan</a>
                        @endif
                    </nav>
                </div>

                <div class="pt-4 border-t border-slate-800 space-y-2">
                    <a href="{{ route('profile.index') }}" class="w-full py-2 px-3 rounded-xl bg-sky-500/10 text-sky-400 font-semibold text-xs flex items-center justify-center gap-2 border border-sky-500/20">
                        <span class="material-symbols-outlined text-sm">account_circle</span> Profil Saya
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full py-2 px-3 rounded-xl bg-rose-500/10 text-rose-400 font-semibold text-xs flex items-center justify-center gap-2 border border-rose-500/20">
                            <span class="material-symbols-outlined text-sm">logout</span> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Body Scroll Area -->
        <main class="flex-1 overflow-y-auto p-4 md:p-8 space-y-6">

            @yield('content')

        </main>
    </div>

    <!-- SweetAlert Notification Handling -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
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

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Akses Ditolak / Kesalahan!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#0284c7',
                    background: '#0f172a',
                    color: '#f8fafc'
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Periksa Inputan Anda!',
                    html: `
                        <ul class="text-left text-xs space-y-1 text-rose-300 pl-4 list-disc">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    `,
                    confirmButtonColor: '#0284c7'
                });
            @endif
        });

        // Global Helper for SweetAlert Delete Confirmation
        function confirmDelete(form, itemName = 'data ini') {
            event.preventDefault();
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: `Penghapusan ${itemName} tidak dapat dibatalkan!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#334155',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            return false;
        }
    </script>

    @yield('scripts')
</body>
</html>
