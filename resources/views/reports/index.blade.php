@extends('layouts.app')

@section('title', 'Laporan Dana Masuk & Dana Keluar')
@section('page_title', 'Laporan Keuangan & Cashflow Real-Time')

@section('content')

<!-- Official Printable Header Banner for Print PDF -->
<div class="print-header">
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #0f172a; padding-bottom: 12px; margin-bottom: 16px;">
        <div>
            <h1 style="font-size: 20pt; font-weight: 800; color: #0f172a; margin: 0;">TRAVEL MANAGER</h1>
            <p style="font-size: 10pt; color: #475569; margin: 2px 0 0 0;">Laporan Keuangan Operasional, Arus Kas & Maintenance Fleet</p>
        </div>
        <div style="text-align: right; font-size: 9pt; color: #475569;">
            <p style="margin: 0; font-weight: 600;">Waktu Cetak: {{ now()->format('d F Y H:i') }} WIB</p>
            <p style="margin: 2px 0 0 0;">Mode Laporan: {{ strtoupper($periodType) }}</p>
        </div>
    </div>
</div>

<div id="reportCaptureArea" class="space-y-6">

    <!-- Header Filter Controls -->
    <div class="glass-panel p-5 rounded-2xl space-y-4">
        
        <!-- Mode Periode Switcher -->
        <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-800">
            <div>
                <h2 class="text-lg font-display font-bold text-white">Filter Periode Cashflow</h2>
                <p class="text-xs text-slate-400">Pilih skema laporan: Semua Data, Per Hari, Per Bulan, atau Per Tahun.</p>
            </div>

            <div class="flex flex-wrap items-center gap-1.5 bg-slate-900 p-1.5 rounded-xl border border-slate-800 text-xs font-semibold no-print">
                <a href="{{ route('reports.index', ['period_type' => 'semua', 'vehicle_id' => $selectedVehicleId]) }}"
                   class="px-3.5 py-2 rounded-lg transition-all flex items-center gap-1.5 {{ $periodType === 'semua' ? 'bg-sky-600 text-white shadow-lg shadow-sky-600/30' : 'text-slate-400 hover:text-white' }}">
                    <span class="material-symbols-outlined text-sm">database</span> Semua Data
                </a>

                <a href="{{ route('reports.index', ['period_type' => 'harian', 'start_date' => $startDate, 'end_date' => $endDate, 'vehicle_id' => $selectedVehicleId]) }}"
                   class="px-3.5 py-2 rounded-lg transition-all flex items-center gap-1.5 {{ $periodType === 'harian' ? 'bg-sky-600 text-white shadow-lg shadow-sky-600/30' : 'text-slate-400 hover:text-white' }}">
                    <span class="material-symbols-outlined text-sm">calendar_view_day</span> Per Hari
                </a>

                <a href="{{ route('reports.index', ['period_type' => 'bulanan', 'year' => $selectedYear, 'vehicle_id' => $selectedVehicleId]) }}"
                   class="px-3.5 py-2 rounded-lg transition-all flex items-center gap-1.5 {{ $periodType === 'bulanan' ? 'bg-sky-600 text-white shadow-lg shadow-sky-600/30' : 'text-slate-400 hover:text-white' }}">
                    <span class="material-symbols-outlined text-sm">calendar_view_month</span> Per Bulan
                </a>

                <a href="{{ route('reports.index', ['period_type' => 'tahunan', 'vehicle_id' => $selectedVehicleId]) }}"
                   class="px-3.5 py-2 rounded-lg transition-all flex items-center gap-1.5 {{ $periodType === 'tahunan' ? 'bg-sky-600 text-white shadow-lg shadow-sky-600/30' : 'text-slate-400 hover:text-white' }}">
                    <span class="material-symbols-outlined text-sm">date_range</span> Per Tahun
                </a>
            </div>
        </div>

        <!-- Filter Inputs Form & Export Action Buttons -->
        <form method="GET" action="{{ route('reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            <input type="hidden" name="period_type" value="{{ $periodType }}">

            @if($periodType === 'harian')
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-xs text-white focus:outline-none focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Tanggal Sampai</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-xs text-white focus:outline-none focus:border-sky-500">
                </div>
            @elseif($periodType === 'bulanan')
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Pilih Tahun</label>
                    <select name="year" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-xs text-white focus:outline-none focus:border-sky-500">
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block pb-2">Menampilkan 12 bulan pada tahun {{ $selectedYear }}</span>
                </div>
            @elseif($periodType === 'tahunan')
                <div>
                    <span class="text-xs text-slate-400 block pb-2">Menampilkan rekapitulasi performa per tahunan</span>
                </div>
            @else
                <div>
                    <span class="text-xs text-emerald-400 font-medium block pb-2">✔ Menampilkan akumulasi seluruh data database</span>
                </div>
            @endif

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Filter Armada Kendaraan</label>
                <select name="vehicle_id" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-xs text-white focus:outline-none focus:border-sky-500">
                    <option value="">-- Semua Kendaraan Armada --</option>
                    @foreach($vehicles as $v)
                        <option value="{{ $v->id }}" {{ $selectedVehicleId == $v->id ? 'selected' : '' }}>
                            {{ $v->plat_nomor }} ({{ $v->merk }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Action Buttons: Filter, Print PDF & Download Image PNG -->
            <div class="flex flex-wrap items-center gap-2 no-print">
                <button type="submit" class="flex-1 px-3.5 py-2 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-semibold text-xs flex items-center justify-center gap-1.5 transition-all shadow-lg shadow-sky-600/20">
                    <span class="material-symbols-outlined text-sm">filter_alt</span> Terapkan
                </button>
                <button type="button" onclick="window.print()" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold text-xs flex items-center gap-1.5 transition-all border border-slate-700">
                    <span class="material-symbols-outlined text-sm text-sky-400">print</span> Print PDF
                </button>
                <button type="button" onclick="downloadReportImage()" class="px-3.5 py-2 rounded-xl bg-emerald-600/20 hover:bg-emerald-600/30 text-emerald-400 font-semibold text-xs flex items-center gap-1.5 transition-all border border-emerald-500/30">
                    <span class="material-symbols-outlined text-sm">image</span> Download Gambar
                </button>
            </div>
        </form>
    </div>

    <!-- Cashflow Metric Bento Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">

        <!-- Total Dana Masuk Card -->
        <div class="glass-card p-5 rounded-2xl border-t-4 border-emerald-500 relative overflow-hidden">
            <div class="flex items-center justify-between text-slate-400 text-xs font-semibold uppercase">
                <span>Total Dana Masuk</span>
                <div class="w-7 h-7 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center border border-emerald-500/20">
                    <span class="material-symbols-outlined text-sm">south_west</span>
                </div>
            </div>
            <h3 class="font-display font-bold text-xl md:text-2xl text-emerald-400 mt-2">
                Rp {{ number_format($totalDanaMasuk, 0, ',', '.') }}
            </h3>
            <p class="text-[11px] text-emerald-300/80 mt-1 font-medium">Akumulasi total tarif booking</p>
        </div>

        <!-- Total Sisa Pelunasan (Piutang) Card -->
        <div class="glass-card p-5 rounded-2xl border-t-4 border-amber-500 relative overflow-hidden">
            <div class="flex items-center justify-between text-slate-400 text-xs font-semibold uppercase">
                <span>Sisa Kurang Bayar (Piutang)</span>
                <div class="w-7 h-7 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center border border-amber-500/20">
                    <span class="material-symbols-outlined text-sm">pending_actions</span>
                </div>
            </div>
            <h3 class="font-display font-bold text-xl md:text-2xl text-amber-400 mt-2">
                Rp {{ number_format($totalSisaPelunasan, 0, ',', '.') }}
            </h3>
            <p class="text-[11px] text-amber-300/80 mt-1 font-medium">Total sisa pelunasan status DP/Belum Lunas</p>
        </div>

        <!-- Total Dana Keluar Card -->
        <div class="glass-card p-5 rounded-2xl border-t-4 border-rose-500 relative overflow-hidden">
            <div class="flex items-center justify-between text-slate-400 text-xs font-semibold uppercase">
                <span>Total Dana Keluar</span>
                <div class="w-7 h-7 rounded-lg bg-rose-500/10 text-rose-400 flex items-center justify-center border border-rose-500/20">
                    <span class="material-symbols-outlined text-sm">north_east</span>
                </div>
            </div>
            <h3 class="font-display font-bold text-xl md:text-2xl text-rose-400 mt-2">
                Rp {{ number_format($totalDanaKeluar, 0, ',', '.') }}
            </h3>
            <p class="text-[11px] text-slate-400 mt-1">
                Ops: Rp {{ number_format($totalPengeluaranOperasional, 0, ',', '.') }} | Servis: Rp {{ number_format($totalBiayaMaintenance, 0, ',', '.') }}
            </p>
        </div>

        <!-- Net Cashflow Saldo Card -->
        <div class="glass-card p-5 rounded-2xl border-t-4 {{ $labaRugiSederhana >= 0 ? 'border-sky-500' : 'border-rose-500' }} relative overflow-hidden">
            <div class="flex items-center justify-between text-slate-400 text-xs font-semibold uppercase">
                <span>Saldo Bersih / Laba Rugi</span>
                <div class="w-7 h-7 rounded-lg {{ $labaRugiSederhana >= 0 ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' }} flex items-center justify-center">
                    <span class="material-symbols-outlined text-sm">account_balance_wallet</span>
                </div>
            </div>
            <h3 class="font-display font-bold text-xl md:text-2xl {{ $labaRugiSederhana >= 0 ? 'text-sky-400' : 'text-rose-400' }} mt-2">
                Rp {{ number_format($labaRugiSederhana, 0, ',', '.') }}
            </h3>
            <p class="text-[11px] text-slate-400 mt-1 font-medium">
                Status: {{ $labaRugiSederhana >= 0 ? 'SURPLUS' : 'DEFISIT' }}
            </p>
        </div>

    </div>

    <!-- Time-Series Breakdown Summary Table (Harian / Bulanan / Tahunan / Semua Data) -->
    <div class="glass-panel rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-sky-400">table_chart</span>
                <h3 class="font-display font-bold text-white text-base">
                    Ringkasan Rekapitulasi Arus Kas - {{ ucfirst($periodType) }}
                </h3>
            </div>
            <span class="text-xs text-slate-400 bg-slate-900 px-3 py-1 rounded-full border border-slate-800">
                Mode: {{ strtoupper($periodType) }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-800">
                        <th class="pb-3 font-semibold">Periode (Hari & Tanggal)</th>
                        <th class="pb-3 font-semibold text-emerald-400">Dana Masuk (Pemasukan)</th>
                        <th class="pb-3 font-semibold text-amber-400">Dana Keluar Operasional</th>
                        <th class="pb-3 font-semibold text-rose-400">Dana Keluar Servis</th>
                        <th class="pb-3 font-semibold text-rose-300">Total Dana Keluar</th>
                        <th class="pb-3 font-semibold text-sky-400">Saldo Bersih</th>
                        <th class="pb-3 font-semibold text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($cashflowData as $row)
                    <tr class="hover:bg-slate-900/40 transition-all">
                        <td class="py-3.5">
                            <span class="font-bold text-white block">{{ $row['label'] }}</span>
                            <span class="text-[11px] text-sky-400 font-semibold">{{ $row['sublabel'] }}</span>
                        </td>
                        <td class="py-3.5 font-semibold text-emerald-400">
                            Rp {{ number_format($row['dana_masuk'], 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 text-amber-300">
                            Rp {{ number_format($row['dana_keluar_ops'], 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 text-rose-400">
                            Rp {{ number_format($row['dana_keluar_srv'], 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 font-semibold text-rose-300">
                            Rp {{ number_format($row['total_dana_keluar'], 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 font-bold {{ $row['saldo'] >= 0 ? 'text-sky-400' : 'text-amber-400' }}">
                            Rp {{ number_format($row['saldo'], 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 text-right whitespace-nowrap">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                {{ $row['saldo'] >= 0 ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20' }}">
                                {{ $row['saldo'] >= 0 ? 'Surplus' : 'Defisit' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-500">Tidak ada data transaksi untuk periode ini.</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-slate-700 bg-slate-900/80 font-bold">
                        <td class="py-3.5 text-white">TOTAL REKAPITULASI REAL-TIME</td>
                        <td class="py-3.5 text-emerald-400">Rp {{ number_format($totalDanaMasuk, 0, ',', '.') }}</td>
                        <td class="py-3.5 text-amber-300">Rp {{ number_format($totalPengeluaranOperasional, 0, ',', '.') }}</td>
                        <td class="py-3.5 text-rose-400">Rp {{ number_format($totalBiayaMaintenance, 0, ',', '.') }}</td>
                        <td class="py-3.5 text-rose-300">Rp {{ number_format($totalDanaKeluar, 0, ',', '.') }}</td>
                        <td class="py-3.5 {{ $labaRugiSederhana >= 0 ? 'text-sky-400' : 'text-rose-400' }}">Rp {{ number_format($labaRugiSederhana, 0, ',', '.') }}</td>
                        <td class="py-3.5 text-right">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase {{ $labaRugiSederhana >= 0 ? 'bg-emerald-500 text-slate-950' : 'bg-rose-500 text-slate-950' }}">
                                {{ $labaRugiSederhana >= 0 ? 'Laba' : 'Rugi' }}
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- SECTION 1: Detail Dana Masuk (Pemasukan Ticket Travel) -->
    <div class="glass-panel rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-400">payments</span>
                <div>
                    <h3 class="font-display font-bold text-white text-base">1. Detail Dana Masuk & Status Pelunasan (DP / Kurang Bayar)</h3>
                    <p class="text-xs text-slate-400">Rincian transaksi pemasukan: Hari, Tanggal, Nama Sopir, DP Dibayar, Total Tarif & Sisa Kurang Bayar.</p>
                </div>
            </div>
            <span class="text-xs text-emerald-400 font-semibold bg-emerald-500/10 border border-emerald-500/20 px-3 py-1 rounded-full">
                Data Terdaftar
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-800">
                        <th class="pb-3 font-semibold">Hari & Tanggal</th>
                        <th class="pb-3 font-semibold">Nama Sopir (Driver)</th>
                        <th class="pb-3 font-semibold">Rute Perjalanan</th>
                        <th class="pb-3 font-semibold text-amber-400">DP Dibayar (Rp)</th>
                        <th class="pb-3 font-semibold text-emerald-400">Total Tarif (Rp)</th>
                        <th class="pb-3 font-semibold text-rose-400">Sisa Pelunasan</th>
                        <th class="pb-3 font-semibold text-right">Status Bayar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($detailDanaMasuk as $item)
                    <tr class="hover:bg-slate-900/40 transition-all">
                        <td class="py-3.5 whitespace-nowrap">
                            <span class="font-bold text-white block">{{ $item->tanggal_berangkat->format('d F Y') }}</span>
                            <span class="text-[11px] text-sky-400 font-semibold flex items-center gap-1">
                                <span class="material-symbols-outlined text-xs">calendar_today</span>
                                {{ $item->tanggal_berangkat->translatedFormat('l') }}
                            </span>
                        </td>
                        <td class="py-3.5">
                            @if($item->driver)
                                <div class="flex items-center gap-2 text-white font-semibold">
                                    <div class="w-7 h-7 rounded-full bg-slate-800 flex items-center justify-center text-sky-400 font-bold border border-slate-700 text-xs">
                                        {{ strtoupper(substr($item->driver->nama, 0, 1)) }}
                                    </div>
                                    {{ $item->driver->nama }}
                                </div>
                            @else
                                <span class="text-slate-500 italic">Sopir Belum Ditentukan</span>
                            @endif
                        </td>
                        <td class="py-3.5 text-slate-200">
                            <span class="font-medium block">{{ $item->asal }} → {{ $item->tujuan }}</span>
                            <span class="text-[11px] text-slate-400">{{ $item->jumlah_kursi }} Seat</span>
                        </td>
                        <td class="py-3.5 font-bold text-amber-400">
                            Rp {{ number_format($item->harga_dp, 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 font-bold text-emerald-400 text-sm">
                            Rp {{ number_format($item->tarif, 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 font-bold whitespace-nowrap">
                            @if($item->status_pembayaran === 'Lunas')
                                <span class="text-emerald-400 font-semibold">Rp 0 (Lunas)</span>
                            @else
                                <span class="text-rose-400 font-bold">Rp {{ number_format($item->sisa_pelunasan, 0, ',', '.') }}</span>
                            @endif
                        </td>
                        <td class="py-3.5 text-right whitespace-nowrap">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ $item->status_pembayaran === 'Lunas' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : ($item->status_pembayaran === 'DP' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20') }}">
                                {{ $item->status_pembayaran }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-6 text-center text-slate-500">Belum ada data detail dana masuk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- SECTION 2: Detail Dana Keluar Operasional (BBM, Tol, Parkir, Dll) -->
    <div class="glass-panel rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-400">local_gas_station</span>
                <div>
                    <h3 class="font-display font-bold text-white text-base">2. Detail Dana Keluar Operasional (BBM, Tol, Parkir Real)</h3>
                    <p class="text-xs text-slate-400">Rincian pengeluaran operasional harian sesuai database: Hari, Tanggal, Kategori, Terkait Rute & Nominal.</p>
                </div>
            </div>
            <span class="text-xs text-amber-400 font-semibold bg-amber-500/10 border border-amber-500/20 px-3 py-1 rounded-full">
                Data Terdaftar
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-800">
                        <th class="pb-3 font-semibold">Hari & Tanggal</th>
                        <th class="pb-3 font-semibold">Kategori Pengeluaran</th>
                        <th class="pb-3 font-semibold">Terkait Rute / Armada</th>
                        <th class="pb-3 font-semibold text-rose-400">Nominal Dana Keluar</th>
                        <th class="pb-3 font-semibold">Keterangan Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($detailDanaKeluarOps as $exp)
                    <tr class="hover:bg-slate-900/40 transition-all">
                        <td class="py-3.5 whitespace-nowrap">
                            <span class="font-bold text-white block">{{ $exp->tanggal->format('d F Y') }}</span>
                            <span class="text-[11px] text-amber-400 font-semibold flex items-center gap-1">
                                <span class="material-symbols-outlined text-xs">calendar_today</span>
                                {{ $exp->tanggal->translatedFormat('l') }}
                            </span>
                        </td>
                        <td class="py-3.5">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ $exp->kategori === 'BBM' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : ($exp->kategori === 'Tol' ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20' : ($exp->kategori === 'Parkir' ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20')) }}">
                                {{ $exp->kategori }}
                            </span>
                        </td>
                        <td class="py-3.5 text-slate-200">
                            @if($exp->schedule)
                                <span class="font-medium block">{{ $exp->schedule->rute }}</span>
                                <span class="text-[11px] text-slate-400">Mobil: {{ $exp->schedule->vehicle->plat_nomor ?? '-' }}</span>
                            @else
                                <span class="text-slate-500 italic">Pengeluaran Operasional Umum</span>
                            @endif
                        </td>
                        <td class="py-3.5 font-bold text-rose-400 text-sm">
                            Rp {{ number_format($exp->jumlah, 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 text-slate-300">
                            {{ $exp->keterangan ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-6 text-center text-slate-500">Belum ada data detail pengeluaran operasional.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- SECTION 3: Detail Dana Keluar Perawatan Kendaraan (Maintenance & Servis Bengkel) -->
    <div class="glass-panel rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-rose-400">build</span>
                <div>
                    <h3 class="font-display font-bold text-white text-base">3. Detail Dana Keluar Perawatan Kendaraan (Servis Bengkel Real)</h3>
                    <p class="text-xs text-slate-400">Rincian biaya servis & perawatan bengkel sesuai database: Hari, Tanggal, Plat Mobil, Jenis Perawatan & Biaya.</p>
                </div>
            </div>
            <span class="text-xs text-rose-400 font-semibold bg-rose-500/10 border border-rose-500/20 px-3 py-1 rounded-full">
                Data Terdaftar
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-800">
                        <th class="pb-3 font-semibold">Hari & Tanggal</th>
                        <th class="pb-3 font-semibold">Armada Mobil</th>
                        <th class="pb-3 font-semibold">Jenis Perawatan (Service)</th>
                        <th class="pb-3 font-semibold">Tujuan Perawatan</th>
                        <th class="pb-3 font-semibold text-rose-400">Biaya Maintenance</th>
                        <th class="pb-3 font-semibold">Catatan Bengkel</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($detailDanaKeluarSrv as $srv)
                    <tr class="hover:bg-slate-900/40 transition-all">
                        <td class="py-3.5 whitespace-nowrap">
                            <span class="font-bold text-white block">{{ $srv->tanggal_perawatan->format('d F Y') }}</span>
                            <span class="text-[11px] text-rose-400 font-semibold flex items-center gap-1">
                                <span class="material-symbols-outlined text-xs">calendar_today</span>
                                {{ $srv->tanggal_perawatan->translatedFormat('l') }}
                            </span>
                        </td>
                        <td class="py-3.5">
                            <span class="font-bold text-white block">{{ $srv->vehicle->plat_nomor ?? '-' }}</span>
                            <span class="text-[11px] text-slate-400">{{ $srv->vehicle->merk ?? '' }}</span>
                        </td>
                        <td class="py-3.5">
                            <div class="flex flex-wrap gap-1">
                                @if(is_array($srv->jenis_perawatan))
                                    @foreach($srv->jenis_perawatan as $j)
                                        <span class="px-2 py-0.5 rounded-md bg-slate-800 text-sky-300 text-[11px] border border-slate-700">
                                            {{ $j }}
                                        </span>
                                    @endforeach
                                @endif
                            </div>
                        </td>
                        <td class="py-3.5">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ $srv->tujuan_perawatan === 'Perbaikan' ? 'bg-rose-500/10 text-rose-400 border border-rose-500/20' : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' }}">
                                {{ $srv->tujuan_perawatan }}
                            </span>
                        </td>
                        <td class="py-3.5 font-bold text-rose-400 text-sm">
                            Rp {{ number_format($srv->biaya, 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 text-slate-300">
                            {{ $srv->catatan ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-6 text-center text-slate-500">Belum ada data detail perawatan kendaraan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
    function downloadReportImage() {
        Swal.fire({
            title: 'Meng-generate Gambar Laporan...',
            text: 'Mohon tunggu sebentar...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const reportElement = document.getElementById('reportCaptureArea');

        html2canvas(reportElement, {
            scale: 2,
            useCORS: true,
            backgroundColor: '#0f172a'
        }).then(canvas => {
            const link = document.createElement('a');
            const dateStr = new Date().toISOString().slice(0, 10);
            link.download = `Laporan_Keuangan_TravelManager_${dateStr}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();

            Swal.fire({
                icon: 'success',
                title: 'Gambar Berhasil Di-download!',
                text: 'Berkas PNG laporan keuangan telah disimpan ke folder Download Anda.',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                background: '#0f172a',
                color: '#f8fafc'
            });
        }).catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Gagal Mengunduh Gambar!',
                text: 'Terjadi kesalahan teknis saat meng-generate gambar laporan.',
            });
        });
    }
</script>
@endsection
