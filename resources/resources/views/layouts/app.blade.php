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
    </style>
</head>
<body class="h-full flex font-sans antialiased text-slate-200 bg-slate-950">

    <!-- Sidebar -->
    <aside class="w-64 glass-panel border-r border-slate-800 flex flex-col justify-between hidden md:flex shrink-0">
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

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1.5">
                <a href="{{ url('/') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all {{ request()->is('/') || request()->is('dashboard') ? 'bg-sky-600/20 text-sky-400 border border-sky-500/30' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                    <span class="material-symbols-outlined text-[20px]">dashboard</span>
                    Dashboard
                </a>

                <a href="{{ route('vehicles.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all {{ request()->is('vehicles*') ? 'bg-sky-600/20 text-sky-400 border border-sky-500/30' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                    <span class="material-symbols-outlined text-[20px]">directions_car</span>
                    Data Kendaraan
                </a>

                <a href="{{ route('drivers.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all {{ request()->is('drivers*') ? 'bg-sky-600/20 text-sky-400 border border-sky-500/30' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                    <span class="material-symbols-outlined text-[20px]">badge</span>
                    Data Sopir
                </a>

                <a href="{{ route('bookings.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all {{ request()->is('bookings*') ? 'bg-sky-600/20 text-sky-400 border border-sky-500/30' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                    <span class="material-symbols-outlined text-[20px]">confirmation_number</span>
                    Booking Travel
                </a>

                <a href="{{ route('schedules.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all {{ request()->is('schedules*') ? 'bg-sky-600/20 text-sky-400 border border-sky-500/30' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                    <span class="material-symbols-outlined text-[20px]">calendar_today</span>
                    Jadwal Perjalanan
                </a>

                <a href="{{ route('maintenances.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all {{ request()->is('maintenances*') ? 'bg-sky-600/20 text-sky-400 border border-sky-500/30' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                    <span class="material-symbols-outlined text-[20px]">build</span>
                    Perawatan Kendaraan
                </a>

                <a href="{{ route('expenses.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all {{ request()->is('expenses*') ? 'bg-sky-600/20 text-sky-400 border border-sky-500/30' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                    <span class="material-symbols-outlined text-[20px]">payments</span>
                    Pengeluaran
                </a>

                <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all {{ request()->is('reports*') ? 'bg-sky-600/20 text-sky-400 border border-sky-500/30' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                    <span class="material-symbols-outlined text-[20px]">analytics</span>
                    Laporan Sederhana
                </a>
            </nav>
        </div>

        <!-- Footer Profile -->
        <div class="p-4 border-t border-slate-800/60">
            <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-slate-900/60 border border-slate-800">
                <div class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center font-bold text-sky-400 text-xs">
                    OP
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-semibold text-white truncate">Administrator</p>
                    <p class="text-xs text-slate-400 truncate">Operasional Travel</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Container -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <!-- Top Header Bar -->
        <header class="h-16 glass-panel border-b border-slate-800 px-6 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <!-- Mobile Navigation Toggle -->
                <button onclick="document.getElementById('mobile-sidebar').classList.toggle('hidden')" class="md:hidden p-2 text-slate-400 hover:text-white rounded-lg bg-slate-800">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <h2 class="font-display font-semibold text-lg text-white">@yield('page_title', 'Dashboard')</h2>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-800/80 text-slate-300 text-xs border border-slate-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>System Active</span>
                </div>
                <div class="text-xs text-slate-400">
                    {{ now()->translatedFormat('l, d F Y') }}
                </div>
            </div>
        </header>

        <!-- Mobile Sidebar Drawer -->
        <div id="mobile-sidebar" class="hidden md:hidden fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md">
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
                        <a href="{{ route('vehicles.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-slate-300 hover:bg-slate-800">Data Kendaraan</a>
                        <a href="{{ route('drivers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-slate-300 hover:bg-slate-800">Data Sopir</a>
                        <a href="{{ route('bookings.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-slate-300 hover:bg-slate-800">Booking Travel</a>
                        <a href="{{ route('schedules.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-slate-300 hover:bg-slate-800">Jadwal Perjalanan</a>
                        <a href="{{ route('maintenances.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-slate-300 hover:bg-slate-800">Perawatan Kendaraan</a>
                        <a href="{{ route('expenses.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-slate-300 hover:bg-slate-800">Pengeluaran Operasional</a>
                        <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-slate-300 hover:bg-slate-800">Laporan</a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main Body Scroll Area -->
        <main class="flex-1 overflow-y-auto p-4 md:p-8 space-y-6">

            <!-- Flash Success Message -->
            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 flex items-center justify-between shadow-lg">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-emerald-400">check_circle</span>
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-200">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </button>
                </div>
            @endif

            <!-- Flash Error Message -->
            @if(session('error') || $errors->any())
                <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 space-y-2 shadow-lg">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-rose-400">warning</span>
                        <p class="text-sm font-medium">Terjadi kesalahan input data:</p>
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-1 text-rose-200 pl-6">
                        @if(session('error'))
                            <li>{{ session('error') }}</li>
                        @endif
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @yield('scripts')
</body>
</html>
