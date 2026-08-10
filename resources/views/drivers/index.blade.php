@extends('layouts.app')

@section('title', 'Data Driver & Profil Pass')
@section('page_title', auth()->check() && auth()->user()->isSupir() ? 'Profil Saya (Digital Driver Pass)' : 'Kelola Data Sopir & Password')

@section('content')

@if(auth()->check() && auth()->user()->isSupir())
    <!-- ==================== TAMPILAN KHUSUS SUPIR (DIGITAL DRIVER PASS) ==================== -->
    <div class="space-y-6">

        <!-- Top Welcome Banner & Upload Trigger -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-display font-bold text-white">Kartu Lisensi & Profil Pengemudi</h2>
                <p class="text-xs text-slate-400">Lisensi Pengemudi TravelManager, verifikasi KTP/SIM, serta riwayat penugasan armada Anda.</p>
            </div>
            <button onclick="openSupirProfileModal()" class="px-4 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-semibold text-xs flex items-center gap-2 shadow-lg shadow-amber-600/20 transition-all">
                <span class="material-symbols-outlined text-sm">edit</span>
                Edit Profil & Ganti Password / Upload KTP & SIM
            </button>
        </div>

        <!-- Catatan Verifikasi Jika Ditolak -->
        @if(($myDriverRecord->status_verifikasi ?? '') === 'Ditolak' && !empty($myDriverRecord->catatan_verifikasi))
        <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-300 text-xs flex items-start gap-3">
            <span class="material-symbols-outlined text-rose-400">warning</span>
            <div>
                <strong class="font-bold text-rose-400 block mb-0.5">Dokumen KTP/SIM Ditolak oleh Owner!</strong>
                <p class="text-[11px] text-slate-300">Catatan Owner: {{ $myDriverRecord->catatan_verifikasi }}</p>
                <p class="text-[10px] text-rose-400 mt-1 font-semibold">Silakan klik tombol "Edit Profil & Upload KTP / SIM" untuk mengunggah ulang foto dokumen yang jelas.</p>
            </div>
        </div>
        @endif

        <!-- 2 Column Layout: Kartu ID Pass & Stat Performa -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Col 1: Kartu ID Digital Driver (Metallic Glass ID Pass) -->
            <div class="glass-card p-6 rounded-3xl border border-amber-500/30 relative overflow-hidden flex flex-col justify-between space-y-6">
                <!-- Background Accent Glow -->
                <div class="absolute -right-12 -top-12 w-36 h-36 bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>

                <div class="space-y-5">
                    <!-- Card Top Brand Bar -->
                    <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-amber-500/20 text-amber-400 flex items-center justify-center font-bold text-xs border border-amber-500/30">
                                TM
                            </div>
                            <span class="font-display font-bold text-sm text-white tracking-wide">TRAVEL MANAGER PASS</span>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                            {{ ($myDriverRecord->status_verifikasi ?? '') === 'Terverifikasi' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : (($myDriverRecord->status_verifikasi ?? '') === 'Menunggu Verifikasi' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20 animate-pulse' : 'bg-slate-800 text-slate-400') }}">
                            {{ $myDriverRecord->status_verifikasi ?? 'Belum Upload' }}
                        </span>
                    </div>

                    <!-- Profile Avatar & Details -->
                    <div class="flex items-center gap-4">
                        @if(!empty($myDriverRecord->foto_profil))
                            <img src="{{ asset('storage/' . $myDriverRecord->foto_profil) }}" alt="Foto Profil" class="w-16 h-16 rounded-2xl object-cover border-2 border-amber-500 shadow-xl shrink-0">
                        @else
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-amber-500 to-indigo-600 flex items-center justify-center text-white font-extrabold text-2xl shadow-xl shadow-amber-500/20 border-2 border-slate-800 shrink-0">
                                {{ strtoupper(substr($myDriverRecord->nama ?? auth()->user()->name, 0, 2)) }}
                            </div>
                        @endif
                        <div class="overflow-hidden">
                            <h3 class="font-display font-bold text-lg text-white truncate">{{ $myDriverRecord->nama ?? auth()->user()->name }}</h3>
                            <p class="text-xs text-amber-400 font-semibold font-mono">ID: DRV-{{ str_pad($myDriverRecord->id ?? 1, 4, '0', STR_PAD_LEFT) }}</p>
                            <p class="text-[11px] text-slate-400 truncate">Login HP: {{ $myDriverRecord->nomor_hp ?? auth()->user()->no_hp ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- SIM & Kontak Grid Info -->
                    <div class="space-y-3 pt-2">
                        <div class="p-3 rounded-xl bg-slate-900/90 border border-slate-800 flex items-center justify-between">
                            <span class="text-xs text-slate-400 font-medium flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-sm text-amber-400">badge</span> Lisensi SIM:
                            </span>
                            <span class="font-mono font-bold text-white text-xs bg-slate-800 px-2 py-1 rounded border border-slate-700">
                                {{ $myDriverRecord->nomor_sim ?? 'SIM-A-982145' }}
                            </span>
                        </div>

                        <div class="p-3 rounded-xl bg-slate-900/90 border border-slate-800 flex items-center justify-between">
                            <span class="text-xs text-slate-400 font-medium flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-sm text-sky-400">call</span> Nomor HP Login:
                            </span>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $myDriverRecord->nomor_hp ?? '') }}" target="_blank" class="font-semibold text-sky-400 hover:underline text-xs flex items-center gap-1">
                                {{ $myDriverRecord->nomor_hp ?? '-' }}
                                <span class="material-symbols-outlined text-xs">open_in_new</span>
                            </a>
                        </div>
                    </div>

                    <!-- Document Upload Previews (KTP & SIM) -->
                    <div class="space-y-2 pt-2 border-t border-slate-800">
                        <p class="text-xs font-semibold text-slate-300 flex items-center justify-between">
                            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-amber-400">folder_shared</span> Dokumen KTP & SIM:</span>
                            <span class="text-[10px] text-amber-400">{{ $myDriverRecord->status_verifikasi ?? 'Belum Upload' }}</span>
                        </p>
                        <div class="grid grid-cols-2 gap-2">
                            <!-- KTP Preview Card -->
                            <div class="p-2.5 rounded-xl bg-slate-900/80 border border-slate-800 text-center space-y-1.5">
                                <span class="text-[10px] text-slate-400 font-semibold block">Foto Dokumen KTP</span>
                                @if(!empty($myDriverRecord->foto_ktp))
                                    <a href="{{ asset('storage/' . $myDriverRecord->foto_ktp) }}" target="_blank" class="block group relative overflow-hidden rounded-lg border border-slate-700">
                                        <img src="{{ asset('storage/' . $myDriverRecord->foto_ktp) }}" class="w-full h-20 object-cover group-hover:scale-105 transition-transform">
                                        <div class="absolute inset-0 bg-slate-950/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <span class="text-[10px] text-sky-400 font-bold flex items-center gap-1">
                                                <span class="material-symbols-outlined text-xs">zoom_in</span> Lihat KTP
                                            </span>
                                        </div>
                                    </a>
                                @else
                                    <div onclick="openSupirProfileModal()" class="h-20 rounded-lg bg-slate-950/50 border border-dashed border-slate-700 flex flex-col items-center justify-center text-[10px] text-slate-500 hover:border-amber-500/50 cursor-pointer">
                                        <span class="material-symbols-outlined text-sm text-amber-400">upload</span>
                                        Klik Upload KTP
                                    </div>
                                @endif
                            </div>

                            <!-- SIM Preview Card -->
                            <div class="p-2.5 rounded-xl bg-slate-900/80 border border-slate-800 text-center space-y-1.5">
                                <span class="text-[10px] text-slate-400 font-semibold block">Foto Dokumen SIM</span>
                                @if(!empty($myDriverRecord->foto_sim))
                                    <a href="{{ asset('storage/' . $myDriverRecord->foto_sim) }}" target="_blank" class="block group relative overflow-hidden rounded-lg border border-slate-700">
                                        <img src="{{ asset('storage/' . $myDriverRecord->foto_sim) }}" class="w-full h-20 object-cover group-hover:scale-105 transition-transform">
                                        <div class="absolute inset-0 bg-slate-950/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <span class="text-[10px] text-amber-400 font-bold flex items-center gap-1">
                                                <span class="material-symbols-outlined text-xs">zoom_in</span> Lihat SIM
                                            </span>
                                        </div>
                                    </a>
                                @else
                                    <div onclick="openSupirProfileModal()" class="h-20 rounded-lg bg-slate-950/50 border border-dashed border-slate-700 flex flex-col items-center justify-center text-[10px] text-slate-500 hover:border-amber-500/50 cursor-pointer">
                                        <span class="material-symbols-outlined text-sm text-amber-400">upload</span>
                                        Klik Upload SIM
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Bottom Status -->
                <div class="pt-4 border-t border-slate-800/80 flex items-center justify-between text-[11px] text-slate-400">
                    <span>Status Tugas: 
                        <strong class="{{ ($myDriverRecord->status_aktif ?? '') === 'Aktif' ? 'text-emerald-400' : (($myDriverRecord->status_aktif ?? '') === 'Sedang Jalan' ? 'text-amber-400' : 'text-slate-400') }}">
                            {{ $myDriverRecord->status_aktif ?? 'Aktif' }}
                        </strong>
                    </span>
                    <button onclick="openSupirProfileModal()" class="text-amber-400 font-semibold hover:underline text-[11px]">
                        Edit Profil / Ganti Pass
                    </button>
                </div>
            </div>

            <!-- Col 2 & 3: Statistics & History Timeline -->
            <div class="lg:col-span-2 space-y-6">

                <!-- 2 Metric Mini Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="glass-panel p-5 rounded-2xl border border-slate-800 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center border border-amber-500/20">
                            <span class="material-symbols-outlined text-2xl">route</span>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">Total Perjalanan Sewa</p>
                            <h4 class="font-display font-extrabold text-xl text-white mt-0.5">{{ $myDriverRecord->schedules_count ?? 0 }} Trip</h4>
                        </div>
                    </div>

                    <div class="glass-panel p-5 rounded-2xl border border-slate-800 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-sky-500/10 text-sky-400 flex items-center justify-center border border-sky-500/20">
                            <span class="material-symbols-outlined text-2xl">confirmation_number</span>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">Total Booking Terhubung</p>
                            <h4 class="font-display font-extrabold text-xl text-white mt-0.5">{{ $myDriverRecord->bookings_count ?? 0 }} Reservasi</h4>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Penugasan Terbaru -->
                <div class="glass-panel rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-amber-400">history</span>
                            <h3 class="font-display font-bold text-white text-base">Riwayat Tugas Perjalanan Terbaru</h3>
                        </div>
                        <a href="{{ route('schedules.index') }}" class="text-xs text-amber-400 hover:text-amber-300 font-semibold flex items-center gap-1">
                            Lihat Jadwal Lengkap <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="text-slate-400 border-b border-slate-800">
                                    <th class="pb-3 font-semibold">Tgl Berangkat</th>
                                    <th class="pb-3 font-semibold">Armada Mobil</th>
                                    <th class="pb-3 font-semibold">Rute Perjalanan</th>
                                    <th class="pb-3 font-semibold text-right">Status Trip</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60">
                                @forelse($mySchedulesHistory as $sch)
                                <tr>
                                    <td class="py-3 whitespace-nowrap">
                                        <span class="font-bold text-white block">{{ $sch->tanggal_keberangkatan->format('d M Y, H:i') }} WIB</span>
                                    </td>
                                    <td class="py-3">
                                        <span class="font-semibold text-white block">{{ $sch->vehicle->plat_nomor ?? '-' }}</span>
                                        <span class="text-[11px] text-slate-400">{{ $sch->vehicle->merk ?? '' }}</span>
                                    </td>
                                    <td class="py-3 text-slate-200 font-medium">
                                        {{ $sch->rute }}
                                    </td>
                                    <td class="py-3 text-right whitespace-nowrap">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                            {{ $sch->status_perjalanan === 'Selesai' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : ($sch->status_perjalanan === 'Dalam Perjalanan' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20 animate-pulse' : 'bg-sky-500/10 text-sky-400 border border-sky-500/20') }}">
                                            {{ $sch->status_perjalanan }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-slate-500">Belum ada riwayat perjalanan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Modal Edit Profil Khusus Supir -->
    <div id="supirProfileModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="glass-panel w-full max-w-lg rounded-2xl p-6 shadow-2xl border border-amber-500/30 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                <h3 class="font-display font-bold text-lg text-white">Edit Profil & Upload KTP / SIM</h3>
                <button onclick="closeSupirProfileModal()" class="text-slate-400 hover:text-white">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form action="{{ route('drivers.update', $myDriverRecord->id ?? 1) }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4 text-xs">
                @csrf
                @method('PUT')

                <div>
                    <label class="block font-medium text-slate-300 mb-1">Nama Lengkap Driver</label>
                    <input type="text" name="nama" value="{{ old('nama', $myDriverRecord->nama ?? auth()->user()->name) }}" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-amber-500">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium text-slate-300 mb-1">Nomor WhatsApp / HP (Username Login)</label>
                        <input type="text" name="nomor_hp" value="{{ old('nomor_hp', $myDriverRecord->nomor_hp ?? '') }}" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-amber-500">
                    </div>

                    <div>
                        <label class="block font-medium text-slate-300 mb-1">Nomor SIM Driver</label>
                        <input type="text" name="nomor_sim" value="{{ old('nomor_sim', $myDriverRecord->nomor_sim ?? '') }}" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-amber-500">
                    </div>
                </div>

                <!-- Section Ganti Password Mandiri Supir -->
                <div class="p-3.5 rounded-xl bg-slate-900/90 border border-amber-500/30 space-y-3">
                    <p class="text-xs font-semibold text-amber-400 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">lock_reset</span> Ganti Kata Sandi Akun (Password)
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-medium text-slate-300 mb-1">Password Baru</label>
                            <input type="password" name="password" placeholder="Minimal 4 Karakter" class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-700 text-white focus:outline-none focus:border-amber-500">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-300 mb-1">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" placeholder="Ulangi Password Baru" class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-700 text-white focus:outline-none focus:border-amber-500">
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400 italic">* Kosongkan password jika Anda tidak ingin mengubah kata sandi akun.</p>
                </div>

                <div class="space-y-3 pt-2 border-t border-slate-800">
                    <div>
                        <label class="block font-medium text-slate-300 mb-1">Upload Foto Profil Avatar (Opsional)</label>
                        <input type="file" name="foto_profil" accept="image/*" class="w-full text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-600/20 file:text-amber-400 hover:file:bg-amber-600/30">
                    </div>

                    <div>
                        <label class="block font-medium text-slate-300 mb-1">Upload Foto Dokumen KTP (Kartu Tanda Penduduk)</label>
                        <input type="file" name="foto_ktp" accept="image/*" class="w-full text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-sky-600/20 file:text-sky-400 hover:file:bg-sky-600/30">
                    </div>

                    <div>
                        <label class="block font-medium text-slate-300 mb-1">Upload Foto Dokumen SIM (Surat Izin Mengemudi)</label>
                        <input type="file" name="foto_sim" accept="image/*" class="w-full text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-600/20 file:text-emerald-400 hover:file:bg-emerald-600/30">
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-800 flex justify-end gap-2">
                    <button type="button" onclick="closeSupirProfileModal()" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 font-semibold hover:bg-slate-700">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-amber-600 text-white font-semibold hover:bg-amber-500 shadow-lg shadow-amber-600/20">Simpan Perubahan & Berkas</button>
                </div>
            </form>
        </div>
    </div>

@else
    <!-- ==================== TAMPILAN KELOLA DRIVER KHUSUS OWNER ==================== -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-display font-bold text-white">Manajemen Driver & Password Akun</h2>
            <p class="text-xs text-slate-400">Kelola data pengemudi, lihat Nomor HP & Password login driver, serta lakukan verifikasi dokumen KTP & SIM.</p>
        </div>
        <button onclick="openCreateModal()" class="px-4 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-semibold text-xs flex items-center gap-2 shadow-lg shadow-sky-600/20 transition-all">
            <span class="material-symbols-outlined text-sm">person_add</span>
            Tambah Sopir Baru
        </button>
    </div>

    <!-- Drivers Table View -->
    <div class="glass-panel rounded-2xl p-6 mt-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-800">
                        <th class="pb-3 font-semibold">Nama Driver</th>
                        <th class="pb-3 font-semibold text-sky-400">Kredensial Login (No HP & Kata Sandi)</th>
                        <th class="pb-3 font-semibold">Dokumen KTP & SIM</th>
                        <th class="pb-3 font-semibold">Status Verifikasi</th>
                        <th class="pb-3 font-semibold text-right">Aksi & Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($drivers as $driver)
                    <tr>
                        <td class="py-3.5">
                            <div class="flex items-center gap-3">
                                @if(!empty($driver->foto_profil))
                                    <img src="{{ asset('storage/' . $driver->foto_profil) }}" class="w-9 h-9 rounded-full object-cover border border-slate-700">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-slate-800 flex items-center justify-center text-sky-400 font-bold border border-slate-700">
                                        {{ strtoupper(substr($driver->nama, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <span class="font-semibold text-white block">{{ $driver->nama }}</span>
                                    <span class="text-[11px] text-slate-400">SIM: {{ $driver->nomor_sim }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5 text-slate-200">
                            <div class="space-y-1">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $driver->nomor_hp) }}" target="_blank" class="flex items-center gap-1 text-sky-400 hover:underline font-bold">
                                    <span class="material-symbols-outlined text-xs">call</span> HP: {{ $driver->nomor_hp }}
                                </a>
                                <div class="flex items-center gap-1.5 text-[11px]">
                                    <span class="material-symbols-outlined text-xs text-amber-400">key</span>
                                    <span class="text-slate-400">Password:</span>
                                    <span class="px-2 py-0.5 rounded bg-slate-800 text-amber-400 font-mono font-bold border border-slate-700">
                                        {{ $driver->user->password_hint ?? 'password' }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5">
                            <div class="flex items-center gap-1.5">
                                @if($driver->foto_ktp)
                                    <a href="{{ asset('storage/' . $driver->foto_ktp) }}" target="_blank" class="px-2 py-0.5 rounded bg-sky-500/10 text-sky-400 border border-sky-500/20 text-[10px] font-semibold flex items-center gap-0.5 hover:underline">
                                        <span class="material-symbols-outlined text-xs">id_card</span> KTP
                                    </a>
                                @else
                                    <span class="text-[10px] text-slate-500 italic">No KTP</span>
                                @endif

                                @if($driver->foto_sim)
                                    <a href="{{ asset('storage/' . $driver->foto_sim) }}" target="_blank" class="px-2 py-0.5 rounded bg-amber-500/10 text-amber-400 border border-amber-500/20 text-[10px] font-semibold flex items-center gap-0.5 hover:underline">
                                        <span class="material-symbols-outlined text-xs">badge</span> SIM
                                    </a>
                                @else
                                    <span class="text-[10px] text-slate-500 italic">No SIM</span>
                                @endif
                            </div>
                        </td>
                        <td class="py-3.5">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ $driver->status_verifikasi === 'Terverifikasi' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : ($driver->status_verifikasi === 'Menunggu Verifikasi' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20 animate-pulse' : ($driver->status_verifikasi === 'Ditolak' ? 'bg-rose-500/10 text-rose-400 border border-rose-500/20' : 'bg-slate-800 text-slate-400 border border-slate-700')) }}">
                                {{ $driver->status_verifikasi }}
                            </span>
                        </td>
                        <td class="py-3.5 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick='openVerifyModal(@json($driver))' class="px-2.5 py-1.5 rounded-lg bg-amber-600/20 hover:bg-amber-600/30 text-amber-400 font-semibold text-xs transition-all flex items-center gap-1 border border-amber-500/30">
                                    <span class="material-symbols-outlined text-sm">fact_check</span> Verifikasi Berkas
                                </button>
                                <button onclick='openEditModal(@json($driver))' class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 transition-all flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">edit</span>
                                </button>
                                <form action="{{ route('drivers.destroy', $driver->id) }}" method="POST" onsubmit="return confirmDelete(this, 'driver {{ $driver->nama }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 transition-all flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-500">Belum ada data driver terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Owner Verifikasi Berkas KTP & SIM -->
    <div id="verifyModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="glass-panel w-full max-w-xl rounded-2xl p-6 shadow-2xl border border-amber-500/30 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                <div>
                    <h3 class="font-display font-bold text-lg text-white">Verifikasi Dokumen KTP & SIM</h3>
                    <p id="verifyDriverName" class="text-xs text-amber-400 font-medium"></p>
                </div>
                <button onclick="closeVerifyModal()" class="text-slate-400 hover:text-white">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <!-- Image Previews Container -->
            <div class="mt-4 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1 text-center">
                        <span class="text-xs font-semibold text-slate-300 block">Dokumen KTP</span>
                        <div id="verifyKtpPreview" class="h-44 rounded-xl bg-slate-900 border border-slate-800 overflow-hidden flex items-center justify-center">
                            <span class="text-xs text-slate-500">KTP Belum Diunggah</span>
                        </div>
                    </div>

                    <div class="space-y-1 text-center">
                        <span class="text-xs font-semibold text-slate-300 block">Dokumen SIM</span>
                        <div id="verifySimPreview" class="h-44 rounded-xl bg-slate-900 border border-slate-800 overflow-hidden flex items-center justify-center">
                            <span class="text-xs text-slate-500">SIM Belum Diunggah</span>
                        </div>
                    </div>
                </div>

                <form id="verifyForm" method="POST" action="" class="space-y-4 text-xs pt-4 border-t border-slate-800">
                    @csrf
                    <div>
                        <label class="block font-medium text-slate-300 mb-1">Keputusan Owner</label>
                        <select id="status_verifikasi" name="status_verifikasi" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-amber-500 font-semibold">
                            <option value="Terverifikasi">✔ Setujui / Terverifikasi (Berkas Valid)</option>
                            <option value="Ditolak">✖ Tolak Berkas (Instruksikan Upload Ulang)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium text-slate-300 mb-1">Catatan Owner (Opsional)</label>
                        <textarea id="catatan_verifikasi" name="catatan_verifikasi" rows="3" placeholder="Contoh: Foto SIM kurang jelas, mohon foto ulang dengan pencahayaan terang." class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-amber-500"></textarea>
                    </div>

                    <div class="pt-4 border-t border-slate-800 flex justify-end gap-2">
                        <button type="button" onclick="closeVerifyModal()" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 font-semibold hover:bg-slate-700">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-500 shadow-lg shadow-emerald-600/20">Simpan Verifikasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Owner Tambah/Edit Driver -->
    <div id="driverModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="glass-panel w-full max-w-md rounded-2xl p-6 shadow-2xl border border-slate-700 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                <h3 id="modalTitle" class="font-display font-bold text-lg text-white">Tambah Sopir Baru</h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-white">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form id="driverForm" method="POST" action="{{ route('drivers.store') }}" enctype="multipart/form-data" class="mt-4 space-y-4 text-xs">
                @csrf
                <input type="hidden" id="methodField" name="_method" value="POST">

                <div>
                    <label class="block font-medium text-slate-300 mb-1">Nama Lengkap Sopir</label>
                    <input type="text" id="nama" name="nama" placeholder="Bambang Sudrajat" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block font-medium text-slate-300 mb-1">Nomor WhatsApp / HP (Username Login)</label>
                    <input type="text" id="nomor_hp" name="nomor_hp" placeholder="081298765432" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500 font-mono">
                </div>

                <div>
                    <label class="block font-medium text-slate-300 mb-1">Kata Sandi Login Supir (Password)</label>
                    <input type="text" id="password" name="password" placeholder="Default: password" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-amber-400 font-mono focus:outline-none focus:border-sky-500">
                    <p class="text-[10px] text-slate-400 mt-1">Kosongkan jika tidak ingin mengubah password akun driver.</p>
                </div>

                <div>
                    <label class="block font-medium text-slate-300 mb-1">Nomor SIM Driver</label>
                    <input type="text" id="nomor_sim" name="nomor_sim" placeholder="SIM-A-982145" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500 font-mono">
                </div>

                <div>
                    <label class="block font-medium text-slate-300 mb-1">Status Aktivitas Driver</label>
                    <select id="status_aktif" name="status_aktif" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:border-sky-500 font-semibold">
                        <option value="Aktif">Aktif (SIAP / Standby)</option>
                        <option value="Sedang Jalan">Sedang Jalan (Dalam Perjalanan)</option>
                        <option value="Nonaktif">Nonaktif (Istirahat / Libur)</option>
                    </select>
                </div>

                <div class="pt-4 border-t border-slate-800 flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 font-semibold hover:bg-slate-700">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-sky-600 text-white font-semibold hover:bg-sky-500 shadow-lg shadow-sky-600/20">Simpan Driver</button>
                </div>
            </form>
        </div>
    </div>
@endif

@endsection

@section('scripts')
<script>
    function openSupirProfileModal() {
        document.getElementById('supirProfileModal').classList.remove('hidden');
    }

    function closeSupirProfileModal() {
        document.getElementById('supirProfileModal').classList.add('hidden');
    }

    @if(auth()->check() && auth()->user()->isOwner())
    function openVerifyModal(driver) {
        document.getElementById('verifyDriverName').innerText = "Driver: " + driver.nama + " (ID: DRV-" + String(driver.id).padStart(4, '0') + ")";
        document.getElementById('verifyForm').action = "/drivers/" + driver.id + "/verify";

        const ktpContainer = document.getElementById('verifyKtpPreview');
        if (driver.foto_ktp) {
            ktpContainer.innerHTML = `<a href="/storage/${driver.foto_ktp}" target="_blank" class="w-full h-full block"><img src="/storage/${driver.foto_ktp}" class="w-full h-full object-cover"></a>`;
        } else {
            ktpContainer.innerHTML = `<span class="text-xs text-slate-500">KTP Belum Diunggah</span>`;
        }

        const simContainer = document.getElementById('verifySimPreview');
        if (driver.foto_sim) {
            simContainer.innerHTML = `<a href="/storage/${driver.foto_sim}" target="_blank" class="w-full h-full block"><img src="/storage/${driver.foto_sim}" class="w-full h-full object-cover"></a>`;
        } else {
            simContainer.innerHTML = `<span class="text-xs text-slate-500">SIM Belum Diunggah</span>`;
        }

        document.getElementById('status_verifikasi').value = driver.status_verifikasi === 'Ditolak' ? 'Ditolak' : 'Terverifikasi';
        document.getElementById('catatan_verifikasi').value = driver.catatan_verifikasi || '';

        document.getElementById('verifyModal').classList.remove('hidden');
    }

    function closeVerifyModal() {
        document.getElementById('verifyModal').classList.add('hidden');
    }

    function openCreateModal() {
        document.getElementById('modalTitle').innerText = 'Tambah Sopir Baru';
        document.getElementById('driverForm').action = "{{ route('drivers.store') }}";
        document.getElementById('methodField').value = 'POST';

        document.getElementById('nama').value = '';
        document.getElementById('nomor_hp').value = '';
        document.getElementById('password').value = '';
        document.getElementById('nomor_sim').value = '';
        document.getElementById('status_aktif').value = 'Aktif';

        document.getElementById('driverModal').classList.remove('hidden');
    }

    function openEditModal(driver) {
        document.getElementById('modalTitle').innerText = 'Edit Data Sopir';
        document.getElementById('driverForm').action = "/drivers/" + driver.id;
        document.getElementById('methodField').value = 'PUT';

        document.getElementById('nama').value = driver.nama;
        document.getElementById('nomor_hp').value = driver.nomor_hp;
        document.getElementById('password').value = driver.user ? (driver.user.password_hint || '') : '';
        document.getElementById('nomor_sim').value = driver.nomor_sim;
        document.getElementById('status_aktif').value = driver.status_aktif;

        document.getElementById('driverModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('driverModal').classList.add('hidden');
    }
    @endif
</script>
@endsection
