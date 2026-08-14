@extends('layouts.app')

@section('title', 'Dashboard Operasional Travel')
@section('page_title', auth()->check() && auth()->user()->isSupir() ? 'Mission Control Supir' : 'Executive Command Center Owner')

@section('content')

@if(auth()->check() && auth()->user()->isSupir())
    <!-- ==================== DASHBOARD KHUSUS SUPIR (MISSION CONTROL) ==================== -->
    <div class="space-y-6">

        <!-- Welcome Banner Supir -->
        <div class="glass-panel p-6 md:p-8 rounded-3xl bg-gradient-to-r from-amber-950/40 via-slate-900 to-slate-950 border border-amber-500/30 relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-2xl">
            <div class="absolute -right-16 -bottom-16 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="flex items-center gap-5 z-10">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-600 p-0.5 shadow-xl shadow-amber-500/20 shrink-0">
                    <div class="w-full h-full bg-slate-950 rounded-[14px] flex items-center justify-center text-amber-400">
                        <span class="material-symbols-outlined text-3xl">badge</span>
                    </div>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-xl md:text-2xl font-display font-black text-white">Selamat Bertugas, {{ auth()->user()->name }}!</h2>
                        <span class="px-2.5 py-0.5 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20 text-[10px] font-bold uppercase tracking-wider">Driver Travel Pass</span>
                    </div>
                    <p class="text-xs text-slate-300 mt-1 font-medium flex items-center gap-4 flex-wrap">
                        <span><strong class="text-amber-400">SIM:</strong> {{ $myDriverRecord->nomor_sim ?? 'Terverifikasi' }}</span>
                        <span class="text-slate-600">|</span>
                        <span><strong class="text-amber-400">Status Tugas:</strong> 
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ ($myDriverRecord->status_aktif ?? '') === 'Aktif' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-amber-500/10 text-amber-400' }}">
                                {{ $myDriverRecord->status_aktif ?? 'Aktif' }}
                            </span>
                        </span>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 z-10 flex-wrap">
                <a href="{{ route('schedules.index') }}" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-500 hover:to-orange-500 text-white font-bold text-xs flex items-center gap-2 shadow-lg shadow-amber-600/20 transition-all">
                    <span class="material-symbols-outlined text-base">calendar_today</span> Lihat Jadwal Tugas Saya
                </a>
                <a href="{{ route('drivers.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-200 font-semibold text-xs flex items-center gap-2 border border-slate-700 transition-all">
                    <span class="material-symbols-outlined text-base text-amber-400">account_circle</span> Pass & Profil
                </a>
            </div>
        </div>

        <!-- Tabel Jadwal Tugas Perjalanan Supir -->
        <div class="glass-panel rounded-3xl p-6 border border-slate-800">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 pb-4 border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center border border-amber-500/20">
                        <span class="material-symbols-outlined text-xl">route</span>
                    </div>
                    <div>
                        <h3 class="font-display font-bold text-white text-base">Penugasan Rute & Jadwal Perjalanan Saya</h3>
                        <p class="text-xs text-slate-400">Perbarui status perjalanan secara real-time dari tombol di bawah ini</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-amber-400 bg-amber-500/10 px-3.5 py-1.5 rounded-full border border-amber-500/20 flex items-center gap-1.5 self-start sm:self-auto">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span> Realtime Task Assignment
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-400 border-b border-slate-800/80">
                            <th class="pb-3 font-semibold">Tgl Keberangkatan</th>
                            <th class="pb-3 font-semibold">Armada Mobil</th>
                            <th class="pb-3 font-semibold">Rute Perjalanan</th>
                            <th class="pb-3 font-semibold">Status Trip</th>
                            <th class="pb-3 font-semibold text-right">Aksi Update Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($mySchedules as $schedule)
                        <tr class="hover:bg-slate-900/40 transition-colors">
                            <td class="py-4 whitespace-nowrap">
                                <span class="font-bold text-white block text-sm">{{ $schedule->tanggal_keberangkatan->format('d M Y, H:i') }} WIB</span>
                                <span class="text-[11px] text-amber-400 font-semibold">{{ $schedule->tanggal_keberangkatan->translatedFormat('l') }}</span>
                            </td>
                            <td class="py-4">
                                @if($schedule->vehicle)
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-1 rounded-lg bg-slate-900 text-sky-400 font-mono font-bold text-xs border border-slate-800">
                                            {{ $schedule->vehicle->plat_nomor }}
                                        </span>
                                        <div>
                                            <span class="font-semibold text-white block text-xs">{{ $schedule->vehicle->merk }}</span>
                                            <span class="text-[10px] text-slate-400">{{ $schedule->vehicle->kapasitas }} Seat</span>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-slate-500 italic">Armada Belum Set</span>
                                @endif
                            </td>
                            <td class="py-4 text-slate-200">
                                <span class="font-semibold text-white text-xs block">{{ $schedule->rute }}</span>
                            </td>
                            <td class="py-4 whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold tracking-wider uppercase
                                    {{ $schedule->status_perjalanan === 'Selesai' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : ($schedule->status_perjalanan === 'Dalam Perjalanan' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20 animate-pulse' : 'bg-sky-500/10 text-sky-400 border border-sky-500/20') }}">
                                    {{ $schedule->status_perjalanan }}
                                </span>
                            </td>
                            <td class="py-4 text-right whitespace-nowrap">
                                <form action="{{ route('schedules.update', $schedule->id) }}" method="POST" class="inline-flex gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="vehicle_id" value="{{ $schedule->vehicle_id }}">
                                    <input type="hidden" name="driver_id" value="{{ $schedule->driver_id }}">
                                    <input type="hidden" name="rute" value="{{ $schedule->rute }}">
                                    <input type="hidden" name="tanggal_keberangkatan" value="{{ $schedule->tanggal_keberangkatan->format('Y-m-d\TH:i') }}">

                                    @if($schedule->status_perjalanan === 'Terjadwal')
                                        <input type="hidden" name="status_perjalanan" value="Dalam Perjalanan">
                                        <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs flex items-center gap-1.5 shadow-lg shadow-amber-600/20 transition-all">
                                            <span class="material-symbols-outlined text-base">directions_car</span> Mulai Jalan
                                        </button>
                                    @elseif($schedule->status_perjalanan === 'Dalam Perjalanan')
                                        <input type="hidden" name="status_perjalanan" value="Selesai">
                                        <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs flex items-center gap-1.5 shadow-lg shadow-emerald-600/20 transition-all">
                                            <span class="material-symbols-outlined text-base">check_circle</span> Selesai Perjalanan
                                        </button>
                                    @else
                                        <span class="text-emerald-400 text-xs font-bold flex items-center gap-1 bg-emerald-500/10 px-3 py-1.5 rounded-xl border border-emerald-500/20">
                                            <span class="material-symbols-outlined text-base">task_alt</span> Trip Selesai
                                        </span>
                                    @endif
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-slate-500 font-medium">
                                <span class="material-symbols-outlined text-4xl block text-slate-600 mb-2">event_busy</span>
                                Belum ada penugasan rute perjalanan untuk akun supir ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Widget Informasi Armada Mobil Travel (Tampilan Khusus Supir) -->
        <div class="glass-panel rounded-3xl p-6 border border-slate-800">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-sky-500/10 text-sky-400 flex items-center justify-center border border-sky-500/20">
                        <span class="material-symbols-outlined text-xl">directions_car</span>
                    </div>
                    <div>
                        <h3 class="font-display font-bold text-white text-base">Ketersediaan Armada Travel Hari Ini</h3>
                        <p class="text-xs text-slate-400">Status operasional & spesifikasi mobil travel (Read-Only Supir)</p>
                    </div>
                </div>
                <a href="{{ route('vehicles.index') }}" class="text-xs text-sky-400 hover:text-sky-300 font-semibold flex items-center gap-1">
                    Lihat Semua <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                @forelse($allVehicles as $v)
                <div class="glass-card p-4 rounded-2xl border border-slate-800 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="px-2 py-0.5 rounded bg-slate-900 text-sky-400 font-mono font-bold text-xs border border-slate-700">
                            {{ $v->plat_nomor }}
                        </span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                            {{ $v->status === 'Tersedia' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : ($v->status === 'Beroperasi' ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20') }}">
                            {{ $v->status }}
                        </span>
                    </div>

                    <div>
                        <h4 class="font-bold text-white text-xs truncate">{{ $v->merk }}</h4>
                        <p class="text-[11px] text-slate-400 mt-0.5">{{ $v->kapasitas }} Kursi (Seat)</p>
                    </div>
                </div>
                @empty
                <div class="col-span-5 text-center py-6 text-slate-500 text-xs">Belum ada armada mobil.</div>
                @endforelse
            </div>
        </div>

    </div>

@else
    <!-- ==================== DASHBOARD UTAMA OWNER / MANAGEMENT ==================== -->
    <div class="space-y-6">

        <!-- Welcome Banner Executive Owner -->
        <div class="glass-panel p-6 md:p-8 rounded-3xl bg-gradient-to-r from-sky-950/50 via-slate-900 to-indigo-950/40 border border-sky-500/30 relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-2xl">
            <div class="absolute -right-16 -bottom-16 w-64 h-64 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="flex items-center gap-5 z-10">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-sky-500 via-indigo-500 to-amber-500 p-0.5 shadow-xl shadow-sky-500/30 shrink-0">
                    <div class="w-full h-full bg-slate-950 rounded-[14px] flex items-center justify-center text-sky-400">
                        <span class="material-symbols-outlined text-3xl">admin_panel_settings</span>
                    </div>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-xl md:text-2xl font-display font-black text-white">Selamat Datang, {{ auth()->user()->name ?? 'Owner' }}!</h2>
                        <span class="px-2.5 py-0.5 rounded-full bg-sky-500/10 text-sky-400 border border-sky-500/20 text-[10px] font-bold uppercase tracking-wider">Executive Command Center</span>
                    </div>
                    <p class="text-xs text-sky-200/80 mt-1 font-medium">Monitoring Real-Time Operasional Fleet, Jadwal Perjalanan, Performance Driver, dan Keuangan</p>
                </div>
            </div>

            <div class="flex items-center gap-3 z-10 flex-wrap">
                <a href="{{ route('schedules.index') }}" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white font-bold text-xs flex items-center gap-2 shadow-lg shadow-sky-600/30 transition-all">
                    <span class="material-symbols-outlined text-base">calendar_today</span> Kelola Jadwal Perjalanan
                </a>
                <a href="{{ route('reports.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-200 font-semibold text-xs flex items-center gap-2 border border-slate-700 transition-all">
                    <span class="material-symbols-outlined text-base text-sky-400">analytics</span> Laporan Keuangan
                </a>
            </div>
        </div>

        <!-- 4 Metric Cards (Bento Grid KPI) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Card 1: Pemasukan & Trip Hari Ini -->
            <div class="glass-card p-5 rounded-3xl border-l-4 border-emerald-500 flex items-center justify-between relative overflow-hidden">
                <div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Total Pendapatan Hari Ini</p>
                    <h3 class="font-display font-black text-xl text-emerald-400 font-mono mt-1">Rp {{ number_format($totalPendapatanHariIni, 0, ',', '.') }}</h3>
                    <p class="text-[11px] text-slate-400 mt-1 font-medium">Dari {{ $totalBookingHariIni }} Jadwal & Booking</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center border border-emerald-500/20 shadow-inner shrink-0">
                    <span class="material-symbols-outlined text-2xl">payments</span>
                </div>
            </div>

            <!-- Card 2: Status Armada Mobil -->
            <div class="glass-card p-5 rounded-3xl border-l-4 border-sky-500 flex items-center justify-between relative overflow-hidden">
                <div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Status Armada Mobil</p>
                    <h3 class="font-display font-black text-2xl text-white mt-1">{{ $armadaAktif }} <span class="text-sm font-normal text-slate-400">/ {{ $totalArmada }} Ready</span></h3>
                    <p class="text-[11px] text-sky-400 mt-1 font-semibold">Tersedia & Beroperasi</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-sky-500/10 text-sky-400 flex items-center justify-center border border-sky-500/20 shadow-inner shrink-0">
                    <span class="material-symbols-outlined text-2xl">directions_car</span>
                </div>
            </div>

            <!-- Card 3: Status Driver / Sopir -->
            <div class="glass-card p-5 rounded-3xl border-l-4 border-amber-500 flex items-center justify-between relative overflow-hidden">
                <div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Status Driver / Sopir</p>
                    <h3 class="font-display font-black text-2xl text-amber-400 mt-1">{{ $sopirAktif }} <span class="text-sm font-normal text-slate-400">/ {{ $totalSopir }} Personil</span></h3>
                    <p class="text-[11px] text-amber-300 mt-1 font-semibold">Aktif & Sedang Jalan</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-400 flex items-center justify-center border border-amber-500/20 shadow-inner shrink-0">
                    <span class="material-symbols-outlined text-2xl">badge</span>
                </div>
            </div>

            <!-- Card 4: Pengeluaran Ops Hari Ini -->
            <div class="glass-card p-5 rounded-3xl border-l-4 border-rose-500 flex items-center justify-between relative overflow-hidden">
                <div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Pengeluaran Ops Hari Ini</p>
                    <h3 class="font-display font-black text-xl text-rose-400 mt-1 font-mono">Rp {{ number_format($totalPengeluaranHariIni, 0, ',', '.') }}</h3>
                    <p class="text-[11px] text-slate-400 mt-1">BBM, Tol, & Service</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-400 flex items-center justify-center border border-rose-500/20 shadow-inner shrink-0">
                    <span class="material-symbols-outlined text-2xl">trending_down</span>
                </div>
            </div>

        </div>

        <!-- Section Operasional Hari Ini: Tabel Jadwal Perjalanan Hari Ini -->
        <div class="glass-panel rounded-3xl p-4 sm:p-6 border border-slate-800">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 pb-3 border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-sky-500/10 text-sky-400 flex items-center justify-center border border-sky-500/20">
                        <span class="material-symbols-outlined text-xl">route</span>
                    </div>
                    <div>
                        <h3 class="font-display font-bold text-white text-base">Monitoring Jadwal Perjalanan Hari Ini</h3>
                        <p class="text-xs text-slate-400">Status keberangkatan armada, driver penanggung jawab, dan tarif sewa trip</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 self-start sm:self-auto">
                    <span class="text-xs font-bold text-emerald-400 bg-emerald-500/10 px-3 py-1 rounded-full border border-emerald-500/20 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span> Realtime Schedule
                    </span>
                    <a href="{{ route('schedules.index') }}" class="text-xs text-sky-400 hover:text-sky-300 font-semibold flex items-center gap-1">
                        Kelola Jadwal <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs min-w-[700px]">
                    <thead>
                        <tr class="text-slate-400 border-b border-slate-800">
                            <th class="pb-3.5 font-semibold">Tgl & Jam Berangkat</th>
                            <th class="pb-3.5 font-semibold">Armada Mobil</th>
                            <th class="pb-3.5 font-semibold">Sopir Utama & Pendamping</th>
                            <th class="pb-3.5 font-semibold">Rute Perjalanan</th>
                            <th class="pb-3.5 font-semibold text-emerald-400">Pemasukan (Rp)</th>
                            <th class="pb-3.5 font-semibold text-right">Status Trip</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($schedulesToday as $sch)
                        <tr class="hover:bg-slate-900/40 transition-colors">
                            <td class="py-3.5 whitespace-nowrap">
                                <span class="font-bold text-white block text-xs">{{ $sch->tanggal_keberangkatan->format('d M Y, H:i') }} WIB</span>
                                <span class="text-[11px] text-sky-400 font-semibold">{{ $sch->tanggal_keberangkatan->translatedFormat('l') }}</span>
                            </td>
                            <td class="py-3.5">
                                <span class="px-2 py-0.5 rounded bg-slate-900 text-sky-400 font-mono font-bold text-xs border border-slate-800 inline-block">
                                    {{ $sch->vehicle->plat_nomor ?? '-' }}
                                </span>
                                <span class="text-[11px] text-slate-400 block mt-0.5">{{ $sch->vehicle->merk ?? '' }}</span>
                            </td>
                            <td class="py-3.5">
                                <span class="font-bold text-white block text-xs">{{ $sch->driver->nama ?? '-' }}</span>
                                @if($sch->driver2)
                                    <span class="text-[10px] text-amber-400 block font-semibold">+ {{ $sch->driver2->nama }} (Sopir 2)</span>
                                @endif
                            </td>
                            <td class="py-3.5 text-slate-200">
                                <span class="font-semibold text-white block text-xs">{{ $sch->rute }}</span>
                            </td>
                            <td class="py-3.5 font-mono font-bold text-emerald-400 text-xs whitespace-nowrap">
                                Rp {{ number_format($sch->tarif, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 text-right whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    {{ $sch->status_perjalanan === 'Selesai' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : ($sch->status_perjalanan === 'Dalam Perjalanan' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20 animate-pulse' : 'bg-sky-500/10 text-sky-400 border border-sky-500/20') }}">
                                    {{ $sch->status_perjalanan }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500 font-medium">
                                <span class="material-symbols-outlined text-3xl block text-slate-600 mb-1">event_available</span>
                                Belum ada jadwal perjalanan untuk hari ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2 Column Section: Status Armada & Maintenance Alert -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Left Col (2/3): Ketersediaan Armada Mobil -->
            <div class="lg:col-span-2 glass-panel rounded-3xl p-4 sm:p-6 border border-slate-800">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-sky-500/10 text-sky-400 flex items-center justify-center border border-sky-500/20">
                            <span class="material-symbols-outlined text-xl">directions_car</span>
                        </div>
                        <div>
                            <h3 class="font-display font-bold text-white text-base">Status Ketersediaan Armada Travel</h3>
                            <p class="text-xs text-slate-400">Ringkasan status mobil travel yang siap atau sedang beroperasi</p>
                        </div>
                    </div>
                    <a href="{{ route('vehicles.index') }}" class="text-xs text-sky-400 hover:text-sky-300 font-semibold flex items-center gap-1">
                        Kelola Armada <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                    @forelse($allVehicles as $v)
                    <div class="glass-card p-3.5 rounded-2xl border border-slate-800 space-y-2.5">
                        <div class="flex items-center justify-between">
                            <span class="px-2 py-0.5 rounded bg-slate-900 text-sky-400 font-mono font-bold text-xs border border-slate-700">
                                {{ $v->plat_nomor }}
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                {{ $v->status === 'Tersedia' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : ($v->status === 'Beroperasi' ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20') }}">
                                {{ $v->status }}
                            </span>
                        </div>

                        <div>
                            <h4 class="font-bold text-white text-xs truncate">{{ $v->merk }}</h4>
                            <p class="text-[11px] text-slate-400 mt-0.5">{{ $v->kapasitas }} Kursi (Seat)</p>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-3 text-center py-6 text-slate-500 text-xs">Belum ada armada mobil.</div>
                    @endforelse
                </div>
            </div>

            <!-- Right Col (1/3): Armada Memerlukan Perhatian Servis -->
            <div class="glass-panel rounded-3xl p-4 sm:p-6 border border-slate-800 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-800">
                        <div class="flex items-center gap-2.5">
                            <span class="material-symbols-outlined text-amber-400">build</span>
                            <h3 class="font-display font-bold text-white text-base">Alert Perawatan</h3>
                        </div>
                        <span class="text-[10px] font-bold text-amber-400 bg-amber-500/10 px-2.5 py-0.5 rounded-full border border-amber-500/20">
                            Service Warning
                        </span>
                    </div>

                    <div class="space-y-3">
                        @forelse($kendaraanPerluServis as $v)
                        <div class="p-3 rounded-2xl bg-slate-900/90 border border-amber-500/20 flex items-center justify-between">
                            <div>
                                <span class="px-2 py-0.5 rounded bg-slate-950 text-sky-400 font-mono font-bold text-[10px] border border-slate-800">
                                    {{ $v->plat_nomor }}
                                </span>
                                <h4 class="font-bold text-white text-xs mt-1">{{ $v->merk }}</h4>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                {{ $v->status }}
                            </span>
                        </div>
                        @empty
                        <div class="text-center py-10 text-slate-500 text-xs">
                            <span class="material-symbols-outlined text-3xl block text-emerald-400 mb-1">check_circle</span>
                            Seluruh armada kendaraan dalam kondisi optimal.
                        </div>
                        @endforelse
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-800 mt-4">
                    <a href="{{ route('maintenances.index') }}" class="w-full py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-sky-400 text-xs font-bold flex items-center justify-center gap-1.5 border border-slate-800 transition-all">
                        <span class="material-symbols-outlined text-base">build</span> Kelola Perawatan Armada
                    </a>
                </div>
            </div>

        </div>

    </div>
@endif

@endsection
