@extends('layouts.app')

@section('title', 'Profil Owner Management')
@section('page_title', 'Profil Saya (Owner Management)')

@section('content')
<div class="space-y-6">

    <!-- Top Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl md:text-2xl font-display font-black text-white tracking-wide">Profil Owner & Management Direksi</h2>
            <p class="text-xs text-slate-400 mt-0.5">Pengaturan akun kredensial login Owner, email resmi, nomor WhatsApp notifikasi Fonnte, password, serta kontrol penuh manajemen travel.</p>
        </div>
        <div class="px-4 py-2 rounded-xl bg-sky-500/10 border border-sky-500/20 text-sky-400 text-xs flex items-center gap-1.5 font-bold self-start sm:self-auto">
            <span class="material-symbols-outlined text-base">admin_panel_settings</span> Owner Full Access Administrator
        </div>
    </div>

    <!-- 2 Column Layout: Executive Pass Card & Account Edit Form -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Col 1: Kartu ID Executive Owner (Metallic Sky Glass Card) -->
        <div class="glass-card p-6 rounded-3xl border border-sky-500/30 relative overflow-hidden flex flex-col justify-between space-y-6 shadow-2xl">
            <!-- Background Accent Glow -->
            <div class="absolute -right-12 -top-12 w-36 h-36 bg-sky-500/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="space-y-5">
                <!-- Card Top Brand Bar -->
                <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-sky-500/20 text-sky-400 flex items-center justify-center font-bold text-xs border border-sky-500/30">
                            TM
                        </div>
                        <span class="font-display font-extrabold text-sm text-white tracking-wider">EXECUTIVE OWNER PASS</span>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-sky-500/10 text-sky-400 border border-sky-500/20">
                        OWNER
                    </span>
                </div>

                <!-- Profile Avatar & Details -->
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-sky-500 via-indigo-500 to-amber-500 flex items-center justify-center text-white font-black text-2xl shadow-xl shadow-sky-500/20 border-2 border-slate-800 shrink-0">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div class="overflow-hidden">
                        <h3 class="font-display font-bold text-lg text-white truncate">{{ $user->name }}</h3>
                        <p class="text-xs text-sky-400 font-bold font-mono">ROLE: OWNER / MANAGEMENT</p>
                        <p class="text-[11px] text-slate-400 truncate">{{ $user->email }}</p>
                    </div>
                </div>

                <!-- Nomor WhatsApp Owner Fonnte Card -->
                <div class="p-3.5 rounded-2xl bg-slate-900/90 border border-sky-500/30 flex items-center justify-between">
                    <span class="text-xs text-slate-300 font-medium flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-base text-emerald-400">chat</span> WhatsApp Notifikasi:
                    </span>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->no_hp ?? '089629615301') }}" target="_blank" class="font-bold text-emerald-400 hover:underline text-xs flex items-center gap-1 font-mono">
                        {{ $user->no_hp ?? '089629615301' }}
                        <span class="material-symbols-outlined text-xs">open_in_new</span>
                    </a>
                </div>

                <!-- Permissions List Badge -->
                <div class="space-y-2 pt-2 border-t border-slate-800">
                    <p class="text-xs font-semibold text-slate-300 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm text-sky-400">shield_lock</span> Otoritas & Akses Sistem:
                    </p>
                    <div class="space-y-1.5 text-[11px] text-slate-300">
                        <div class="flex items-center gap-2 p-2 rounded-xl bg-slate-900/80 border border-slate-800">
                            <span class="material-symbols-outlined text-sm text-emerald-400">check_circle</span>
                            <span>Notifikasi Real-time Booking via WhatsApp Fonnte</span>
                        </div>
                        <div class="flex items-center gap-2 p-2 rounded-xl bg-slate-900/80 border border-slate-800">
                            <span class="material-symbols-outlined text-sm text-emerald-400">check_circle</span>
                            <span>Akses Laporan Keuangan & Laba Rugi</span>
                        </div>
                        <div class="flex items-center gap-2 p-2 rounded-xl bg-slate-900/80 border border-slate-800">
                            <span class="material-symbols-outlined text-sm text-emerald-400">check_circle</span>
                            <span>Akses Verifikasi Dokumen KTP & SIM Sopir</span>
                        </div>
                        <div class="flex items-center gap-2 p-2 rounded-xl bg-slate-900/80 border border-slate-800">
                            <span class="material-symbols-outlined text-sm text-emerald-400">check_circle</span>
                            <span>Kelola Master Armada Mobil & Pengeluaran Ops</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Bottom Stats -->
            <div class="pt-4 border-t border-slate-800/80 flex items-center justify-between text-[11px] text-slate-400">
                <span>Kontrol Pengemudi: <strong class="text-white">{{ $totalDrivers }} Driver</strong></span>
                <span>Total Mobil: <strong class="text-sky-400">{{ $totalVehicles }} Unit</strong></span>
            </div>
        </div>

        <!-- Col 2: Form Edit Kredensial Akun Owner -->
        <div class="lg:col-span-2 glass-panel rounded-3xl p-6 border border-slate-800">
            <div class="flex items-center gap-2.5 mb-4 pb-3 border-b border-slate-800">
                <span class="material-symbols-outlined text-sky-400">manage_accounts</span>
                <h3 class="font-display font-bold text-white text-base">Pengaturan Kredensial Akun & WA Notifikasi Owner</h3>
            </div>

            <form action="{{ route('profile.updateOwner') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                @method('PUT')

                <div>
                    <label class="block font-semibold text-slate-300 mb-1.5">Nama Lengkap Owner</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500 font-semibold">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1.5">Email Resmi Owner (Untuk Login)</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500 font-mono">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1.5 flex items-center justify-between">
                            <span>Nomor WhatsApp Owner</span>
                            <span class="text-[10px] text-emerald-400 font-semibold">✔ Aktif Fonnte</span>
                        </label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp ?? '089629615301') }}" required placeholder="089629615301" class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-emerald-400 font-mono font-bold focus:outline-none focus:border-sky-500">
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-800 space-y-4">
                    <p class="text-xs font-bold text-amber-400 flex items-center gap-1">
                        <span class="material-symbols-outlined text-base">lock_reset</span> Ganti Password Akun (Kosongkan jika tidak ingin diubah)
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-slate-300 mb-1.5">Password Baru</label>
                            <input type="password" name="password" placeholder="Minimal 6 karakter" class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-300 mb-1.5">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" placeholder="Ulangi password baru" class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-800 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white font-bold text-xs flex items-center gap-2 shadow-lg shadow-sky-600/30 transition-all">
                        <span class="material-symbols-outlined text-base">save</span>
                        Simpan Perubahan Kredensial & Nomor WA Owner
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>
@endsection
