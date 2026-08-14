<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950 text-slate-100 dark">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'TravelManager') - Luxury Fleet & Travel Operations</title>

    <!-- Google Fonts & Material Symbols -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            400: '#38bdf8',
                            500: '#0284c7',
                            600: '#0369a1',
                            700: '#075985',
                            900: '#0c4a6e',
                            950: '#082f49',
                        },
                        amber: {
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                        }
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'float': 'float 4s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-6px)' },
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
        /* Modern Design System Tokens */
        :root {
            --bg-radial: radial-gradient(circle at 50% -20%, #0f172a 0%, #020617 100%);
        }

        body {
            background-color: #020617;
            background-image: 
                radial-gradient(at 0% 0%, rgba(2, 132, 199, 0.12) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(245, 158, 11, 0.08) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(15, 23, 42, 0.5) 0px, transparent 100%);
            background-attachment: fixed;
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #020617;
        }
        ::-webkit-scrollbar-thumb {
            background: #1e293b;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #0284c7;
        }

        /* Glassmorphism Classes */
        .glass-panel {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
        }

        .glass-card {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.7) 0%, rgba(15, 23, 42, 0.85) 100%);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            border-color: rgba(56, 189, 248, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 12px 30px -10px rgba(2, 132, 199, 0.25);
        }

        .glass-card-amber:hover {
            border-color: rgba(245, 158, 11, 0.4);
            box-shadow: 0 12px 30px -10px rgba(245, 158, 11, 0.25);
        }

        /* Glow Text & Accents */
        .gradient-text-sky {
            background: linear-gradient(135deg, #38bdf8 0%, #818cf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .gradient-text-amber {
            background: linear-gradient(135deg, #fbbf24 0%, #f97316 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .neon-border-sky {
            border-color: rgba(56, 189, 248, 0.3);
            box-shadow: 0 0 15px rgba(56, 189, 248, 0.15);
        }

        /* Custom SweetAlert Dark Styling */
        .swal2-popup {
            background-color: #0b1329 !important;
            color: #f8fafc !important;
            border: 1px solid rgba(56, 189, 248, 0.2) !important;
            border-radius: 1.25rem !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7) !important;
        }
        .swal2-title {
            color: #ffffff !important;
            font-family: 'Outfit', sans-serif !important;
        }
        .swal2-html-container {
            color: #94a3b8 !important;
        }

        /* Print Styles */
        @media print {
            aside, header, .no-print, button, form select, form input {
                display: none !important;
            }
            body {
                background: #ffffff !important;
                color: #0f172a !important;
                font-size: 10pt !important;
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
                padding: 6px 10px !important;
            }
            .print-header {
                display: block !important;
                margin-bottom: 15px;
                border-bottom: 2px solid #0f172a;
                padding-bottom: 8px;
            }
        }
        .print-header {
            display: none;
        }
    </style>
</head>
<body class="h-full flex font-sans antialiased text-slate-200 bg-slate-950 selection:bg-sky-500 selection:text-white">

    <!-- Sidebar -->
    <aside class="w-64 glass-panel border-r border-slate-800/80 flex flex-col justify-between hidden md:flex shrink-0 no-print z-30">
        <div>
            <!-- App Brand Header -->
            <div class="p-5 border-b border-slate-800/80 flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-sky-500 via-indigo-500 to-amber-500 p-0.5 shadow-lg shadow-sky-500/20 group-hover:scale-105 transition-transform duration-300">
                        <div class="w-full h-full bg-slate-950 rounded-[10px] flex items-center justify-center text-sky-400">
                            <span class="material-symbols-outlined text-2xl">directions_bus</span>
                        </div>
                    </div>
                    <div>
                        <h1 class="font-display font-extrabold text-lg text-white tracking-wide leading-tight group-hover:text-sky-400 transition-colors">TravelManager</h1>
                        <p class="text-[10px] text-slate-400 font-semibold tracking-wider uppercase">Fleet Operations</p>
                    </div>
                </a>
            </div>

            <!-- Role Status Badge Card in Sidebar -->
            <div class="px-4 pt-4">
                <div class="p-3 rounded-2xl {{ auth()->check() && auth()->user()->isOwner() ? 'bg-sky-950/40 border border-sky-500/20' : 'bg-amber-950/40 border border-amber-500/20' }} flex items-center gap-3">
                    <div class="w-2.5 h-2.5 rounded-full {{ auth()->check() && auth()->user()->isOwner() ? 'bg-sky-400 animate-ping' : 'bg-amber-400 animate-ping' }}"></div>
                    <div class="overflow-hidden">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Aktif Sebagai</p>
                        <p class="text-xs font-bold font-display {{ auth()->check() && auth()->user()->isOwner() ? 'text-sky-400' : 'text-amber-400' }} truncate">
                            {{ auth()->check() && auth()->user()->isOwner() ? 'Owner / Management' : 'Supir (Driver Pass)' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1.5">
                <p class="px-3 text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-2">Menu Utama</p>

                <!-- 1. Dashboard (Both) -->
                <a href="{{ route('dashboard') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-medium text-xs transition-all {{ request()->is('/') || request()->is('dashboard') ? 'bg-gradient-to-r from-sky-500/20 to-indigo-500/10 text-sky-400 border border-sky-500/30 shadow-lg shadow-sky-500/10 font-bold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[20px]">dashboard</span>
                        <span>Dashboard</span>
                    </div>
                    @if(request()->is('/') || request()->is('dashboard'))
                        <span class="w-1.5 h-1.5 rounded-full bg-sky-400"></span>
                    @endif
                </a>

                @if(auth()->check() && auth()->user()->isOwner())
                <!-- 2. Data Kendaraan (Owner Only) -->
                <a href="{{ route('vehicles.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-medium text-xs transition-all {{ request()->is('vehicles*') ? 'bg-gradient-to-r from-sky-500/20 to-indigo-500/10 text-sky-400 border border-sky-500/30 shadow-lg shadow-sky-500/10 font-bold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[20px]">directions_car</span>
                        <span>Data Kendaraan</span>
                    </div>
                    @if(request()->is('vehicles*'))
                        <span class="w-1.5 h-1.5 rounded-full bg-sky-400"></span>
                    @endif
                </a>

                <!-- 3. Data Sopir (Owner Only) -->
                <a href="{{ route('drivers.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-medium text-xs transition-all {{ request()->is('drivers*') ? 'bg-gradient-to-r from-sky-500/20 to-indigo-500/10 text-sky-400 border border-sky-500/30 shadow-lg shadow-sky-500/10 font-bold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[20px]">badge</span>
                        <span>Data Sopir</span>
                    </div>
                    @if(request()->is('drivers*'))
                        <span class="w-1.5 h-1.5 rounded-full bg-sky-400"></span>
                    @endif
                </a>
                @endif


                <!-- 5. Jadwal Perjalanan (Both) -->
                <a href="{{ route('schedules.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-medium text-xs transition-all {{ request()->is('schedules*') ? 'bg-gradient-to-r from-sky-500/20 to-indigo-500/10 text-sky-400 border border-sky-500/30 shadow-lg shadow-sky-500/10 font-bold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[20px]">calendar_today</span>
                        <span>Jadwal Perjalanan</span>
                    </div>
                    @if(request()->is('schedules*'))
                        <span class="w-1.5 h-1.5 rounded-full bg-sky-400"></span>
                    @endif
                </a>

                <p class="px-3 text-[10px] font-bold uppercase tracking-widest text-slate-500 pt-3 mb-2">Operasional & Log</p>

                <!-- 6. Perawatan Kendaraan (Both Owner & Supir) -->
                <a href="{{ route('maintenances.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-medium text-xs transition-all {{ request()->is('maintenances*') ? 'bg-gradient-to-r from-sky-500/20 to-indigo-500/10 text-sky-400 border border-sky-500/30 shadow-lg shadow-sky-500/10 font-bold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[20px]">build</span>
                        <span>Perawatan Kendaraan</span>
                    </div>
                    @if(request()->is('maintenances*'))
                        <span class="w-1.5 h-1.5 rounded-full bg-sky-400"></span>
                    @endif
                </a>

                <!-- 7. Pengeluaran (Both Owner & Supir) -->
                <a href="{{ route('expenses.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-medium text-xs transition-all {{ request()->is('expenses*') ? 'bg-gradient-to-r from-sky-500/20 to-indigo-500/10 text-sky-400 border border-sky-500/30 shadow-lg shadow-sky-500/10 font-bold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[20px]">payments</span>
                        <span>Pengeluaran</span>
                    </div>
                    @if(request()->is('expenses*'))
                        <span class="w-1.5 h-1.5 rounded-full bg-sky-400"></span>
                    @endif
                </a>

                @if(auth()->check() && auth()->user()->isOwner())
                <!-- 8. Laporan Keuangan (Owner Only) -->
                <a href="{{ route('reports.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-medium text-xs transition-all {{ request()->is('reports*') ? 'bg-gradient-to-r from-sky-500/20 to-indigo-500/10 text-sky-400 border border-sky-500/30 shadow-lg shadow-sky-500/10 font-bold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[20px]">analytics</span>
                        <span>Laporan Keuangan</span>
                    </div>
                    @if(request()->is('reports*'))
                        <span class="w-1.5 h-1.5 rounded-full bg-sky-400"></span>
                    @endif
                </a>
                @endif
            </nav>
        </div>

        <!-- Sidebar Footer Profile & Logout -->
        <div class="p-4 border-t border-slate-800/80 space-y-2">
            <div class="p-2.5 rounded-2xl bg-slate-900/90 border border-slate-800/80 hover:border-slate-700 transition-all">
                <div class="flex items-center justify-between">
                    <a href="{{ route('profile.index') }}" title="Lihat Profil Saya" class="flex items-center gap-2.5 overflow-hidden group">
                        <div class="w-9 h-9 rounded-xl {{ auth()->check() && auth()->user()->isOwner() ? 'bg-gradient-to-tr from-sky-500 to-indigo-600 text-white' : 'bg-gradient-to-tr from-amber-500 to-orange-600 text-white' }} flex items-center justify-center font-bold text-xs shrink-0 shadow-md group-hover:scale-105 transition-transform">
                            {{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 2)) : 'US' }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-xs font-bold text-white truncate group-hover:text-sky-400 transition-colors">{{ auth()->check() ? auth()->user()->name : 'User' }}</p>
                            <p class="text-[10px] text-slate-400 truncate">{{ auth()->check() ? (auth()->user()->isOwner() ? auth()->user()->email : auth()->user()->no_hp) : '-' }}</p>
                        </div>
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Keluar / Logout" class="p-2 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 transition-all flex items-center justify-center">
                            <span class="material-symbols-outlined text-base">logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Container -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <!-- Top Header Bar -->
        <header class="h-16 glass-panel border-b border-slate-800/80 px-6 flex items-center justify-between shrink-0 no-print z-20">
            <div class="flex items-center gap-3">
                <!-- Mobile Sidebar Toggle -->
                <button onclick="document.getElementById('mobile-sidebar').classList.toggle('hidden')" class="md:hidden p-2 text-slate-400 hover:text-white rounded-xl bg-slate-900 border border-slate-800">
                    <span class="material-symbols-outlined text-xl">menu</span>
                </button>
                
                <div>
                    <h2 class="font-display font-bold text-base md:text-lg text-white tracking-wide">@yield('page_title', 'Dashboard')</h2>
                </div>
            </div>
            
            <!-- Header Right Stats & Tools -->
            <div class="flex items-center gap-3">
                
                <!-- Realtime Digital Clock Widget -->
                <div class="hidden lg:flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-slate-900/90 border border-slate-800 text-xs font-mono text-slate-300">
                    <span class="material-symbols-outlined text-sm text-sky-400">schedule</span>
                    <span id="live-clock">{{ now()->format('H:i:s') }} WIB</span>
                    <span class="text-slate-600">|</span>
                    <span class="text-slate-400 text-[11px] font-sans">{{ now()->translatedFormat('d M Y') }}</span>
                </div>

                <!-- User Profile Quick Link -->
                <a href="{{ route('profile.index') }}" title="Lihat Profil Saya" class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl bg-slate-900/90 hover:bg-slate-800/80 border border-slate-800 hover:border-slate-700 transition-all text-xs">
                    <div class="w-6 h-6 rounded-lg {{ auth()->check() && auth()->user()->isOwner() ? 'bg-sky-500/20 text-sky-400' : 'bg-amber-500/20 text-amber-400' }} flex items-center justify-center font-bold text-[10px]">
                        {{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 2)) : 'U' }}
                    </div>
                    <span class="font-semibold text-white hidden sm:inline">{{ auth()->check() ? auth()->user()->name : 'User' }}</span>
                    <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider hidden sm:inline-block {{ auth()->check() && auth()->user()->isOwner() ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20' }}">
                        {{ auth()->check() ? auth()->user()->role : 'guest' }}
                    </span>
                </a>

                <!-- Mobile Logout Button -->
                <form method="POST" action="{{ route('logout') }}" class="sm:hidden">
                    @csrf
                    <button type="submit" class="p-2 rounded-xl bg-rose-500/10 text-rose-400 text-xs font-semibold flex items-center gap-1 border border-rose-500/20">
                        <span class="material-symbols-outlined text-sm">logout</span>
                    </button>
                </form>
            </div>
        </header>

        <!-- Mobile Sidebar Drawer -->
        <div id="mobile-sidebar" onclick="if(event.target === this) this.classList.add('hidden')" class="hidden md:hidden fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md no-print">
            <div class="w-72 h-full bg-slate-950 border-r border-slate-800 p-5 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-sky-500 to-indigo-600 flex items-center justify-center text-white font-bold">
                                <span class="material-symbols-outlined text-xl">directions_bus</span>
                            </div>
                            <span class="font-display font-extrabold text-white text-base">TravelManager</span>
                        </div>
                        <button onclick="document.getElementById('mobile-sidebar').classList.add('hidden')" class="p-1.5 text-slate-400 hover:text-white rounded-lg bg-slate-900">
                            <span class="material-symbols-outlined text-xl">close</span>
                        </button>
                    </div>

                    <nav class="mt-4 space-y-1.5" onclick="document.getElementById('mobile-sidebar').classList.add('hidden')">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium text-slate-300 hover:bg-slate-900">
                            <span class="material-symbols-outlined text-lg text-sky-400">dashboard</span> Dashboard
                        </a>
                        @if(auth()->check() && auth()->user()->isOwner())
                        <a href="{{ route('vehicles.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium text-slate-300 hover:bg-slate-900">
                            <span class="material-symbols-outlined text-lg text-sky-400">directions_car</span> Data Kendaraan
                        </a>
                        <a href="{{ route('drivers.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium text-slate-300 hover:bg-slate-900">
                            <span class="material-symbols-outlined text-lg text-sky-400">badge</span> Data Sopir
                        </a>
                        @endif

                        <a href="{{ route('schedules.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium text-slate-300 hover:bg-slate-900">
                            <span class="material-symbols-outlined text-lg text-sky-400">calendar_today</span> Jadwal Perjalanan
                        </a>
                        <a href="{{ route('maintenances.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium text-slate-300 hover:bg-slate-900">
                            <span class="material-symbols-outlined text-lg text-sky-400">build</span> Perawatan Kendaraan
                        </a>
                        <a href="{{ route('expenses.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium text-slate-300 hover:bg-slate-900">
                            <span class="material-symbols-outlined text-lg text-sky-400">payments</span> Pengeluaran Operasional
                        </a>
                        @if(auth()->check() && auth()->user()->isOwner())
                        <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium text-slate-300 hover:bg-slate-900">
                            <span class="material-symbols-outlined text-lg text-sky-400">analytics</span> Laporan Keuangan
                        </a>
                        @endif
                    </nav>
                </div>

                <div class="pt-4 border-t border-slate-800 space-y-2">
                    <a href="{{ route('profile.index') }}" class="w-full py-2.5 px-3 rounded-xl bg-sky-500/10 text-sky-400 font-semibold text-xs flex items-center justify-center gap-2 border border-sky-500/20">
                        <span class="material-symbols-outlined text-sm">account_circle</span> Profil Saya
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full py-2.5 px-3 rounded-xl bg-rose-500/10 text-rose-400 font-semibold text-xs flex items-center justify-center gap-2 border border-rose-500/20">
                            <span class="material-symbols-outlined text-sm">logout</span> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Body Scroll Area -->
        <main class="flex-1 overflow-y-auto p-3 sm:p-6 md:p-8 space-y-6">

            @yield('content')

        </main>
    </div>

    <!-- Realtime Clock & SweetAlert Notifications -->
    <script>
        // Global Rupiah Currency Input Formatter Helpers
        function formatRupiah(val) {
            if (val === undefined || val === null || val === '') return '';
            let numberString = val.toString().replace(/[^0-9]/g, '');
            if (!numberString) return '';
            return new Intl.NumberFormat('id-ID').format(numberString);
        }

        function unformatRupiah(val) {
            if (!val) return '';
            return val.toString().replace(/\./g, '').replace(/[^0-9]/g, '');
        }

        function setCurrencyInputValue(inputElem, val) {
            if (!inputElem) return;
            if (val === '' || val === null || val === undefined) {
                inputElem.value = '';
            } else {
                inputElem.value = formatRupiah(val);
            }
        }

        function getCurrencyInputValue(inputElem) {
            if (!inputElem) return 0;
            return parseFloat(unformatRupiah(inputElem.value)) || 0;
        }

        // Update Live Clock WIB
        function updateClock() {
            const clockEl = document.getElementById('live-clock');
            if (clockEl) {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                clockEl.textContent = `${hours}:${minutes}:${seconds} WIB`;
            }
        }
        setInterval(updateClock, 1000);

        document.addEventListener("DOMContentLoaded", function() {
            // Live format currency inputs as user types
            document.addEventListener('input', function(e) {
                if (e.target && e.target.classList.contains('currency-input')) {
                    let rawVal = e.target.value;
                    let cursorPosition = e.target.selectionStart;
                    let oldLen = rawVal.length;
                    
                    let formatted = formatRupiah(rawVal);
                    e.target.value = formatted;
                    
                    let newLen = formatted.length;
                    cursorPosition = cursorPosition + (newLen - oldLen);
                    try {
                        e.target.setSelectionRange(cursorPosition, cursorPosition);
                    } catch(err) {}
                }
            });

            // Strip dots before form submission so backend receives raw numeric values
            document.addEventListener('submit', function(e) {
                const form = e.target;
                if (form) {
                    const currencyInputs = form.querySelectorAll('.currency-input');
                    currencyInputs.forEach(input => {
                        input.value = unformatRupiah(input.value);
                    });
                }
            });

            // Initial format for currency inputs that already contain values
            document.querySelectorAll('.currency-input').forEach(input => {
                if (input.value) {
                    input.value = formatRupiah(input.value);
                }
            });
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

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Pemberitahuan!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#0284c7',
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
