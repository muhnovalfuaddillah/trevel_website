<!-- Navigation Bar Publik Website TravelManager -->
<nav class="fixed top-0 left-0 w-full z-50 bg-slate-950/70 backdrop-blur-xl border-b border-slate-800/80 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
        
        <!-- Brand Logo -->
        <a href="{{ url('/') }}" class="flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-sky-500 via-indigo-500 to-amber-500 p-0.5 shadow-lg shadow-sky-500/20 group-hover:scale-105 transition-transform duration-300">
                <div class="w-full h-full bg-slate-950 rounded-[14px] flex items-center justify-center text-sky-400">
                    <span class="material-symbols-outlined text-2xl">directions_bus</span>
                </div>
            </div>
            <div>
                <h1 class="font-display font-black text-lg text-white tracking-wide group-hover:text-sky-400 transition-colors">TravelManager</h1>
                <p class="text-[10px] text-sky-300/80 font-bold uppercase tracking-wider">Executive Fleet & Travel</p>
            </div>
        </a>

        <!-- Desktop Navigation Links -->
        <div class="hidden md:flex items-center gap-8 text-xs font-semibold text-slate-300">
            <a href="{{ url('/') }}#beranda" class="hover:text-sky-400 transition-colors py-1 border-b-2 border-transparent hover:border-sky-400">Beranda</a>
            <a href="{{ url('/') }}#rute" class="hover:text-sky-400 transition-colors py-1 border-b-2 border-transparent hover:border-sky-400">Rute Perjalanan</a>
            <a href="{{ url('/') }}#armada" class="hover:text-sky-400 transition-colors py-1 border-b-2 border-transparent hover:border-sky-400">Armada Mobil</a>
            <a href="{{ url('/') }}#form-order" class="text-emerald-400 hover:text-emerald-300 font-bold transition-colors py-1 border-b-2 border-transparent hover:border-emerald-400 flex items-center gap-1">
                <span class="material-symbols-outlined text-base">edit_note</span> Form Order WA
            </a>
            <a href="{{ url('/') }}#keunggulan" class="hover:text-sky-400 transition-colors py-1 border-b-2 border-transparent hover:border-sky-400">Keunggulan</a>
            <a href="{{ url('/') }}#kontak" class="hover:text-sky-400 transition-colors py-1 border-b-2 border-transparent hover:border-sky-400">Kontak</a>
        </div>

        <!-- Auth Actions / System Portal Link -->
        <div class="hidden md:flex items-center gap-3">
            @auth
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white font-bold text-xs flex items-center gap-2 shadow-lg shadow-sky-600/30 transition-all">
                    <span class="material-symbols-outlined text-base">dashboard</span>
                    Dashboard Saya
                </a>
            @else
                <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white font-bold text-xs flex items-center gap-2 shadow-lg shadow-sky-600/30 transition-all">
                    <span class="material-symbols-outlined text-base">login</span>
                    Portal Login System
                </a>
            @endauth
        </div>

        <!-- Mobile Hamburger Button -->
        <button onclick="document.getElementById('mobile-nav').classList.toggle('hidden')" class="md:hidden p-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 hover:text-white">
            <span class="material-symbols-outlined text-2xl">menu</span>
        </button>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div id="mobile-nav" class="hidden md:hidden bg-slate-950/95 backdrop-blur-2xl border-b border-slate-800 p-6 space-y-4 text-xs font-semibold">
        <a href="{{ url('/') }}#beranda" class="block text-slate-200 hover:text-sky-400">Beranda</a>
        <a href="{{ url('/') }}#rute" class="block text-slate-200 hover:text-sky-400">Rute Perjalanan</a>
        <a href="{{ url('/') }}#armada" class="block text-slate-200 hover:text-sky-400">Armada Mobil</a>
        <a href="{{ url('/') }}#form-order" class="block text-emerald-400 font-bold">Form Order WA</a>
        <a href="{{ url('/') }}#keunggulan" class="block text-slate-200 hover:text-sky-400">Keunggulan</a>
        <a href="{{ url('/') }}#kontak" class="block text-slate-200 hover:text-sky-400">Kontak</a>
        <div class="pt-4 border-t border-slate-800">
            @auth
                <a href="{{ route('dashboard') }}" class="w-full py-3 rounded-xl bg-sky-600 text-white font-bold text-xs flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-base">dashboard</span> Dashboard Saya
                </a>
            @else
                <a href="{{ route('login') }}" class="w-full py-3 rounded-xl bg-sky-600 text-white font-bold text-xs flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-base">login</span> Portal Login System
                </a>
            @endauth
        </div>
    </div>
</nav>
