@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Operasional Travel')

@section('content')

@if(auth()->check() && auth()->user()->isSupir())
    <!-- ==================== DASHBOARD KHUSUS SUPIR ==================== -->
    <div class="space-y-6">

        <!-- Welcome Banner Supir -->
        <div class="glass-panel p-6 rounded-2xl bg-gradient-to-r from-amber-900/30 via-slate-900 to-slate-900 border border-amber-500/20 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/20 text-amber-400 flex items-center justify-center border border-amber-500/30">
                    <span class="material-symbols-outlined text-2xl">badge</span>
                </div>
                <div>
                    <h2 class="text-xl font-display font-bold text-white">Selamat Bertugas, {{ auth()->user()->name }}!</h2>
                    <p class="text-xs text-amber-300/80">Role: Supir (Driver Travel) | SIM: {{ $myDriverRecord->nomor_sim ?? 'Terverifikasi' }} | Status: {{ $myDriverRecord->status_aktif ?? 'Aktif' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('schedules.index') }}" class="px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-semibold text-xs flex items-center gap-1.5 shadow-lg shadow-amber-600/20 transition-all">
                    <span class="material-symbols-outlined text-sm">calendar_today</span> Lihat Jadwal Tugas Saya
                </a>
                <a href="{{ route('drivers.index') }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold text-xs flex items-center gap-1.5 border border-slate-700 transition-all">
                    <span class="material-symbols-outlined text-sm">account_circle</span> Profil & Dokumen
                </a>
            </div>
        </div>

        <!-- Tabel Jadwal Tugas Perjalanan Supir -->
        <div class="glass-panel rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-400">route</span>
                    <h3 class="font-display font-bold text-white text-base">Jadwal Tugas Perjalanan Saya</h3>
                </div>
                <span class="text-xs text-amber-400 bg-amber-500/10 px-3 py-1 rounded-full border border-amber-500/20">
                    Tugas Supir
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-400 border-b border-slate-800">
                            <th class="pb-3 font-semibold">Tgl Keberangkatan</th>
                            <th class="pb-3 font-semibold">Mobil Armada</th>
                            <th class="pb-3 font-semibold">Rute Perjalanan</th>
                            <th class="pb-3 font-semibold">Status Perjalanan</th>
                            <th class="pb-3 font-semibold text-right">Aksi Update Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($mySchedules as $schedule)
                        <tr>
                            <td class="py-3.5 whitespace-nowrap">
                                <span class="font-bold text-white block">{{ $schedule->tanggal_keberangkatan->format('d M Y, H:i') }} WIB</span>
                                <span class="text-[11px] text-amber-400 font-medium">{{ $schedule->tanggal_keberangkatan->translatedFormat('l') }}</span>
                            </td>
                            <td class="py-3.5">
                                @if($schedule->vehicle)
                                    <span class="font-bold text-white block">{{ $schedule->vehicle->plat_nomor }}</span>
                                    <span class="text-[11px] text-slate-400">{{ $schedule->vehicle->merk }} ({{ $schedule->vehicle->kapasitas }} Seat)</span>
                                @else
                                    <span class="text-slate-500 italic">Armada Belum Set</span>
                                @endif
                            </td>
                            <td class="py-3.5 text-slate-200">
                                <span class="font-semibold block">{{ $schedule->rute }}</span>
                            </td>
                            <td class="py-3.5 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                    {{ $schedule->status_perjalanan === 'Selesai' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : ($schedule->status_perjalanan === 'Dalam Perjalanan' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20 animate-pulse' : 'bg-sky-500/10 text-sky-400 border border-sky-500/20') }}">
                                    {{ $schedule->status_perjalanan }}
                                </span>
                            </td>
                            <td class="py-3.5 text-right whitespace-nowrap">
                                <form action="{{ route('schedules.update', $schedule->id) }}" method="POST" class="inline-flex gap-1.5">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="vehicle_id" value="{{ $schedule->vehicle_id }}">
                                    <input type="hidden" name="driver_id" value="{{ $schedule->driver_id }}">
                                    <input type="hidden" name="rute" value="{{ $schedule->rute }}">
                                    <input type="hidden" name="tanggal_keberangkatan" value="{{ $schedule->tanggal_keberangkatan->format('Y-m-d\TH:i') }}">

                                    @if($schedule->status_perjalanan === 'Terjadwal')
                                        <input type="hidden" name="status_perjalanan" value="Dalam Perjalanan">
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-amber-600 hover:bg-amber-500 text-white font-semibold text-xs flex items-center gap-1 shadow-lg shadow-amber-600/20">
                                            <span class="material-symbols-outlined text-sm">directions_car</span> Mulai Jalan
                                        </button>
                                    @elseif($schedule->status_perjalanan === 'Dalam Perjalanan')
                                        <input type="hidden" name="status_perjalanan" value="Selesai">
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs flex items-center gap-1 shadow-lg shadow-emerald-600/20">
                                            <span class="material-symbols-outlined text-sm">check_circle</span> Selesai Perjalanan
                                        </button>
                                    @else
                                        <span class="text-emerald-400 text-xs font-semibold flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">task_alt</span> Perjalanan Selesai
                                        </span>
                                    @endif
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-500">Belum ada penugasan rute perjalanan untuk akun supir ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Widget Informasi Armada Mobil Travel (Tampilan Khusus Supir) -->
        <div class="glass-panel rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-sky-400">directions_car</span>
                    <div>
                        <h3 class="font-display font-bold text-white text-base">Informasi Armada Kendaraan Travel</h3>
                        <p class="text-xs text-slate-400">Status ketersediaan & spesifikasi mobil travel per hari (Read-Only Supir)</p>
                    </div>
                </div>
                <a href="{{ route('vehicles.index') }}" class="text-xs text-sky-400 hover:text-sky-300 font-semibold flex items-center gap-1">
                    Lihat Semua <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                @forelse($allVehicles as $v)
                <div class="glass-card p-4 rounded-xl border border-slate-800 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="px-2 py-0.5 rounded bg-slate-800 text-sky-400 font-mono font-bold text-xs border border-slate-700">
                            {{ $v->plat_nomor }}
                        </span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold
                            {{ $v->status === 'Tersedia' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : ($v->status === 'Beroperasi' ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20') }}">
                            {{ $v->status }}
                        </span>
                    </div>

                    <div>
                        <h4 class="font-bold text-white text-xs truncate">{{ $v->merk }}</h4>
                        <p class="text-[11px] text-slate-400">{{ $v->kapasitas }} Seat (Kursi)</p>
                    </div>

                    <div class="pt-2 border-t border-slate-800/80 flex items-center justify-between text-[11px]">
                        <span class="text-slate-400">Tarif / Hari:</span>
                        <span class="font-bold text-emerald-400">Rp {{ number_format($v->tarif_per_hari, 0, ',', '.') }}</span>
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

        <!-- Welcome Banner Owner -->
        <div class="glass-panel p-6 rounded-2xl bg-gradient-to-r from-sky-900/30 via-slate-900 to-slate-900 border border-sky-500/20 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-sky-500/20 text-sky-400 flex items-center justify-center border border-sky-500/30">
                    <span class="material-symbols-outlined text-2xl">admin_panel_settings</span>
                </div>
                <div>
                    <h2 class="text-xl font-display font-bold text-white">Selamat Datang, {{ auth()->user()->name ?? 'Owner' }}!</h2>
                    <p class="text-xs text-sky-300/80">Peran: Owner & Management | Kontrol Penuh Armada, Booking, Sopir & Keuangan Travel</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('reports.index') }}" class="px-4 py-2 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-semibold text-xs flex items-center gap-1.5 shadow-lg shadow-sky-600/20 transition-all">
                    <span class="material-symbols-outlined text-sm">analytics</span> Laporan Keuangan
                </a>
            </div>
        </div>

        <!-- 4 Bento Grid Highlight Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Card 1: Booking Hari Ini -->
            <div class="glass-card p-5 rounded-2xl border-l-4 border-sky-500 flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-xs font-semibold uppercase">Booking Hari Ini</p>
                    <h3 class="font-display font-bold text-2xl text-white mt-1">{{ $totalBookingHariIni }} Tiket</h3>
                    <p class="text-[11px] text-sky-400 mt-1 font-medium">Rp {{ number_format($totalPendapatanHariIni, 0, ',', '.') }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-sky-500/10 text-sky-400 flex items-center justify-center border border-sky-500/20">
                    <span class="material-symbols-outlined text-xl">confirmation_number</span>
                </div>
            </div>

            <!-- Card 2: Armada Mobil Ready -->
            <div class="glass-card p-5 rounded-2xl border-l-4 border-emerald-500 flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-xs font-semibold uppercase">Armada Mobil Ready</p>
                    <h3 class="font-display font-bold text-2xl text-emerald-400 mt-1">{{ $armadaAktif }} / {{ $totalArmada }}</h3>
                    <p class="text-[11px] text-emerald-300/80 mt-1 font-medium">Siap Beroperasi</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center border border-emerald-500/20">
                    <span class="material-symbols-outlined text-xl">directions_car</span>
                </div>
            </div>

            <!-- Card 3: Sopir Ready -->
            <div class="glass-card p-5 rounded-2xl border-l-4 border-amber-500 flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-xs font-semibold uppercase">Sopir Ready</p>
                    <h3 class="font-display font-bold text-2xl text-amber-400 mt-1">{{ $sopirAktif }} / {{ $totalSopir }}</h3>
                    <p class="text-[11px] text-amber-300/80 mt-1 font-medium">Driver Aktif</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center border border-amber-500/20">
                    <span class="material-symbols-outlined text-xl">badge</span>
                </div>
            </div>

            <!-- Card 4: Pengeluaran Ops Hari Ini -->
            <div class="glass-card p-5 rounded-2xl border-l-4 border-rose-500 flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-xs font-semibold uppercase">Pengeluaran Ops Hari Ini</p>
                    <h3 class="font-display font-bold text-xl text-rose-400 mt-1">Rp {{ number_format($totalPengeluaranHariIni, 0, ',', '.') }}</h3>
                    <p class="text-[11px] text-slate-400 mt-1">BBM, Tol, & Parkir</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-400 flex items-center justify-center border border-rose-500/20">
                    <span class="material-symbols-outlined text-xl">payments</span>
                </div>
            </div>

        </div>

        <!-- 2 Column Section: Booking Terbaru & Perhatian Maintenance -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Left Col (2/3): Transaksi Booking Travel Terbaru -->
            <div class="lg:col-span-2 glass-panel rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-sky-400">confirmation_number</span>
                        <h3 class="font-display font-bold text-white text-base">Booking Tiket Terbaru</h3>
                    </div>
                    <a href="{{ route('bookings.index') }}" class="text-xs text-sky-400 hover:text-sky-300 font-semibold flex items-center gap-1">
                        Lihat Semua <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-slate-400 border-b border-slate-800">
                                <th class="pb-3 font-semibold">Nama Sopir / Mobil</th>
                                <th class="pb-3 font-semibold">Rute & Tgl</th>
                                <th class="pb-3 font-semibold">Total Tarif (Rp)</th>
                                <th class="pb-3 font-semibold text-right">Status Bayar</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @forelse($recentBookings as $b)
                            <tr>
                                <td class="py-3">
                                    <span class="font-medium text-white block">{{ $b->driver->nama ?? 'Sopir Belum Set' }}</span>
                                    <span class="text-[11px] text-slate-400">{{ $b->jumlah_kursi }} Kursi</span>
                                </td>
                                <td class="py-3">
                                    <span class="text-slate-300 block">{{ $b->asal }} → {{ $b->tujuan }}</span>
                                    <span class="text-[11px] text-slate-400">{{ $b->tanggal_berangkat->format('d M Y') }}</span>
                                </td>
                                <td class="py-3 font-semibold text-slate-200">
                                    <span class="block text-amber-400 text-[11px]">DP: Rp {{ number_format($b->harga_dp, 0, ',', '.') }}</span>
                                    <span class="text-emerald-400 font-bold">Rp {{ number_format($b->tarif, 0, ',', '.') }}</span>
                                </td>
                                <td class="py-3 text-right whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider block w-fit ml-auto mb-1
                                        {{ $b->status_pembayaran === 'Lunas' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20' }}">
                                        {{ $b->status_pembayaran }}
                                    </span>
                                    @if($b->status_pembayaran !== 'Lunas')
                                        <span class="text-[10px] text-rose-400 font-semibold block">
                                            Sisa: Rp {{ number_format($b->sisa_pelunasan, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-500">Belum ada booking travel.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right Col (1/3): Armada Perlu Servis Bengkel -->
            <div class="glass-panel rounded-2xl p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="material-symbols-outlined text-rose-400">warning</span>
                        <h3 class="font-display font-bold text-white text-base">Armada Perlu Servis</h3>
                    </div>

                    <div class="space-y-3">
                        @forelse($kendaraanPerluServis as $v)
                        <div class="p-3 rounded-xl bg-slate-900/60 border border-rose-500/20 flex items-center justify-between">
                            <div>
                                <span class="font-bold text-white text-xs block">{{ $v->plat_nomor }}</span>
                                <span class="text-[11px] text-slate-400">{{ $v->merk }}</span>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/20 text-rose-400 border border-rose-500/30">
                                {{ $v->status }}
                            </span>
                        </div>
                        @empty
                        <div class="py-8 text-center text-slate-500 text-xs">
                            <span class="material-symbols-outlined text-2xl text-emerald-400 block mb-1">verified</span>
                            Seluruh armada kendaraan dalam kondisi baik & siap jalan.
                        </div>
                        @endforelse
                    </div>
                </div>

                <div class="pt-4 mt-4 border-t border-slate-800">
                    <a href="{{ route('maintenances.index') }}" class="w-full py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold text-xs flex items-center justify-center gap-2 transition-all border border-slate-700">
                        <span class="material-symbols-outlined text-sm">build</span> Kelola Servis Bengkel
                    </a>
                </div>
            </div>

        </div>
    </div>
@endif

@endsection
