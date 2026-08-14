@extends('layouts.app')

@section('title', 'Laporan Pengeluaran Operasional')
@section('page_title', 'Laporan Pengeluaran Fleet & Operasional')

@section('content')

<!-- Official Printable Header Banner for Print PDF -->
<div class="print-header">
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #0f172a; padding-bottom: 12px; margin-bottom: 16px;">
        <div>
            <h1 style="font-size: 20pt; font-weight: 800; color: #0f172a; margin: 0;">TRAVEL MANAGER</h1>
            <p style="font-size: 10pt; color: #475569; margin: 2px 0 0 0;">Laporan Rincian Pengeluaran Operasional, BBM, Tol & Servis Fleet</p>
        </div>
        <div style="text-align: right; font-size: 9pt; color: #475569;">
            <p style="margin: 0; font-weight: 600;">Waktu Cetak: {{ now()->format('d F Y H:i') }} WIB</p>
            <p style="margin: 2px 0 0 0;">Mode Laporan: {{ strtoupper($periodType) }} {{ $selectedCategory ? '('.$selectedCategory.')' : '' }}</p>
        </div>
    </div>
</div>

<div id="reportCaptureArea" class="space-y-6">

    <!-- Sub-Nav Tab Switcher (Cashflow vs Laporan Pengeluaran) -->
    <div class="flex items-center justify-between gap-4 border-b border-slate-800 pb-4 no-print">
        <div class="flex items-center gap-2">
            <a href="{{ route('reports.index') }}" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-400 hover:text-white hover:bg-slate-800/60 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">payments</span>
                Laporan Keuangan & Cashflow
            </a>
            <a href="{{ route('reports.expenses') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-sky-400 bg-sky-600/20 border border-sky-500/30 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">pie_chart</span>
                Laporan Pengeluaran Operasional
            </a>
        </div>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold flex items-center gap-1.5 border border-slate-700 transition-all">
                <span class="material-symbols-outlined text-sm">print</span> Cetak PDF / Print
            </button>
            <button onclick="exportReportToPNG()" class="px-3.5 py-2 rounded-xl bg-sky-600 hover:bg-sky-500 text-white text-xs font-semibold flex items-center gap-1.5 shadow-lg shadow-sky-600/20 transition-all">
                <span class="material-symbols-outlined text-sm">download</span> Download PNG
            </button>
        </div>
    </div>

    <!-- Header Filter Controls -->
    <div class="glass-panel p-6 rounded-3xl space-y-4 border border-slate-800">
        
        <!-- Mode Periode Switcher -->
        <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-slate-800">
            <div>
                <h2 class="text-xl font-display font-black text-white tracking-wide">Filter Laporan Pengeluaran</h2>
                <p class="text-xs text-slate-400 mt-0.5">Filter berdasarkan rentang tanggal, bulan, tahun, kategori pengeluaran, atau armada kendaraan.</p>
            </div>

            <div class="flex flex-wrap items-center gap-1.5 bg-slate-900 p-1.5 rounded-2xl border border-slate-800 text-xs font-semibold no-print">
                <a href="{{ route('reports.expenses', ['period_type' => 'semua', 'kategori' => $selectedCategory, 'vehicle_id' => $selectedVehicleId]) }}"
                   class="px-4 py-2 rounded-xl transition-all flex items-center gap-1.5 {{ $periodType === 'semua' ? 'bg-gradient-to-r from-sky-600 to-indigo-600 text-white font-bold shadow-lg shadow-sky-600/30' : 'text-slate-400 hover:text-white' }}">
                    <span class="material-symbols-outlined text-sm">database</span> Semua Data
                </a>

                <a href="{{ route('reports.expenses', ['period_type' => 'harian', 'start_date' => $startDate, 'end_date' => $endDate, 'kategori' => $selectedCategory, 'vehicle_id' => $selectedVehicleId]) }}"
                   class="px-4 py-2 rounded-xl transition-all flex items-center gap-1.5 {{ $periodType === 'harian' ? 'bg-gradient-to-r from-sky-600 to-indigo-600 text-white font-bold shadow-lg shadow-sky-600/30' : 'text-slate-400 hover:text-white' }}">
                    <span class="material-symbols-outlined text-sm">calendar_view_day</span> Per Hari
                </a>

                <a href="{{ route('reports.expenses', ['period_type' => 'bulanan', 'year' => $selectedYear, 'kategori' => $selectedCategory, 'vehicle_id' => $selectedVehicleId]) }}"
                   class="px-4 py-2 rounded-xl transition-all flex items-center gap-1.5 {{ $periodType === 'bulanan' ? 'bg-gradient-to-r from-sky-600 to-indigo-600 text-white font-bold shadow-lg shadow-sky-600/30' : 'text-slate-400 hover:text-white' }}">
                    <span class="material-symbols-outlined text-sm">calendar_view_month</span> Per Bulan
                </a>

                <a href="{{ route('reports.expenses', ['period_type' => 'tahunan', 'kategori' => $selectedCategory, 'vehicle_id' => $selectedVehicleId]) }}"
                   class="px-4 py-2 rounded-xl transition-all flex items-center gap-1.5 {{ $periodType === 'tahunan' ? 'bg-gradient-to-r from-sky-600 to-indigo-600 text-white font-bold shadow-lg shadow-sky-600/30' : 'text-slate-400 hover:text-white' }}">
                    <span class="material-symbols-outlined text-sm">date_range</span> Per Tahun
                </a>
            </div>
        </div>

        <!-- Filter Inputs Form -->
        <form method="GET" action="{{ route('reports.expenses') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <input type="hidden" name="period_type" value="{{ $periodType }}">

            @if($periodType === 'harian')
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-xs text-white focus:outline-none focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Tanggal Sampai</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-xs text-white focus:outline-none focus:border-sky-500">
                </div>
            @elseif($periodType === 'bulanan')
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Pilih Tahun</label>
                    <select name="year" class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-xs text-white focus:outline-none focus:border-sky-500 font-semibold">
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="hidden sm:block"></div>
            @else
                <div class="sm:col-span-2"></div>
            @endif

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Kategori Pengeluaran</label>
                <select name="kategori" class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-xs text-white focus:outline-none focus:border-sky-500 font-semibold">
                    <option value="">-- Semua Kategori --</option>
                    <option value="BBM" {{ $selectedCategory === 'BBM' ? 'selected' : '' }}>⛽ BBM (Bahan Bakar)</option>
                    <option value="Tol" {{ $selectedCategory === 'Tol' ? 'selected' : '' }}>🛣️ E-Toll</option>
                    <option value="Parkir" {{ $selectedCategory === 'Parkir' ? 'selected' : '' }}>🅿️ Parkir</option>
                    <option value="Servis kendaraan" {{ $selectedCategory === 'Servis kendaraan' ? 'selected' : '' }}>🔧 Servis & Maintenance</option>
                    <option value="Lainnya" {{ $selectedCategory === 'Lainnya' ? 'selected' : '' }}>📦 Lainnya</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Filter Armada Kendaraan</label>
                <select name="vehicle_id" class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-xs text-white focus:outline-none focus:border-sky-500 font-semibold">
                    <option value="">-- Semua Mobil --</option>
                    @foreach($vehicles as $v)
                        <option value="{{ $v->id }}" {{ $selectedVehicleId == $v->id ? 'selected' : '' }}>
                            {{ $v->plat_nomor }} ({{ $v->merk }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit" class="w-full py-2.5 rounded-2xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs shadow-lg shadow-sky-600/30 transition-all flex items-center justify-center gap-1.5">
                    <span class="material-symbols-outlined text-sm">filter_alt</span> Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- 4 Summary Analytic Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Card 1: Total Pengeluaran Overall -->
        <div class="glass-card p-6 rounded-3xl border border-rose-500/30 relative overflow-hidden flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Seluruh Pengeluaran</span>
                <div class="w-10 h-10 rounded-2xl bg-rose-500/10 text-rose-400 flex items-center justify-center border border-rose-500/20">
                    <span class="material-symbols-outlined text-xl">account_balance_wallet</span>
                </div>
            </div>
            <div>
                <h3 class="font-display font-black text-2xl text-rose-400 font-mono">Rp {{ number_format($totalSemuaPengeluaran, 0, ',', '.') }}</h3>
                <p class="text-[11px] text-slate-400 mt-1">Ops: Rp {{ number_format($totalPengeluaranOps, 0, ',', '.') }} | Servis: Rp {{ number_format($totalBiayaBengkel, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Card 2: Biaya BBM -->
        <div class="glass-card p-6 rounded-3xl border border-amber-500/30 relative overflow-hidden flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Biaya BBM (Bahan Bakar)</span>
                <div class="w-10 h-10 rounded-2xl bg-amber-500/10 text-amber-400 flex items-center justify-center border border-amber-500/20">
                    <span class="material-symbols-outlined text-xl">local_gas_station</span>
                </div>
            </div>
            <div>
                <h3 class="font-display font-black text-2xl text-amber-400 font-mono">Rp {{ number_format($totalBBM, 0, ',', '.') }}</h3>
                @php
                    $pctBBM = $totalSemuaPengeluaran > 0 ? round(($totalBBM / $totalSemuaPengeluaran) * 100, 1) : 0;
                @endphp
                <p class="text-[11px] text-slate-400 mt-1">Kontribusi {{ $pctBBM }}% dari total pengeluaran</p>
            </div>
        </div>

        <!-- Card 3: Biaya Tol & Parkir -->
        <div class="glass-card p-6 rounded-3xl border border-sky-500/30 relative overflow-hidden flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Biaya E-Toll & Parkir</span>
                <div class="w-10 h-10 rounded-2xl bg-sky-500/10 text-sky-400 flex items-center justify-center border border-sky-500/20">
                    <span class="material-symbols-outlined text-xl">add_road</span>
                </div>
            </div>
            <div>
                @php
                    $totalTolParkir = $totalTol + $totalParkir;
                @endphp
                <h3 class="font-display font-black text-2xl text-sky-400 font-mono">Rp {{ number_format($totalTolParkir, 0, ',', '.') }}</h3>
                <p class="text-[11px] text-slate-400 mt-1">Tol: Rp {{ number_format($totalTol, 0, ',', '.') }} | Parkir: Rp {{ number_format($totalParkir, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Card 4: Biaya Maintenance Bengkel -->
        <div class="glass-card p-6 rounded-3xl border border-indigo-500/30 relative overflow-hidden flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Perawatan & Bengkel</span>
                <div class="w-10 h-10 rounded-2xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center border border-indigo-500/20">
                    <span class="material-symbols-outlined text-xl">build</span>
                </div>
            </div>
            <div>
                <h3 class="font-display font-black text-2xl text-indigo-400 font-mono">Rp {{ number_format($totalServisOps, 0, ',', '.') }}</h3>
                <p class="text-[11px] text-slate-400 mt-1">Total servis rutin & perbaikan bengkel</p>
            </div>
        </div>

    </div>

    <!-- Category Distribution & Vehicle Breakdown Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Col 1: Visual Distribution Breakdown per Category -->
        <div class="glass-panel p-6 rounded-3xl space-y-4 border border-slate-800">
            <div class="flex items-center gap-2 pb-3 border-b border-slate-800">
                <span class="material-symbols-outlined text-sky-400">pie_chart</span>
                <h3 class="font-display font-bold text-white text-base">Persentase Pengeluaran</h3>
            </div>

            <div class="space-y-4 text-xs pt-1">
                <!-- BBM -->
                <div class="space-y-1">
                    <div class="flex items-center justify-between font-semibold">
                        <span class="text-amber-400 flex items-center gap-1.5"><span class="material-symbols-outlined text-sm">local_gas_station</span> BBM</span>
                        <span class="font-mono text-white">Rp {{ number_format($totalBBM, 0, ',', '.') }} ({{ $pctBBM }}%)</span>
                    </div>
                    <div class="w-full h-2 rounded-full bg-slate-900 overflow-hidden">
                        <div class="h-full bg-amber-500 rounded-full" style="width: {{ $pctBBM }}%"></div>
                    </div>
                </div>

                <!-- Tol -->
                @php
                    $pctTol = $totalSemuaPengeluaran > 0 ? round(($totalTol / $totalSemuaPengeluaran) * 100, 1) : 0;
                @endphp
                <div class="space-y-1">
                    <div class="flex items-center justify-between font-semibold">
                        <span class="text-sky-400 flex items-center gap-1.5"><span class="material-symbols-outlined text-sm">add_road</span> E-Toll</span>
                        <span class="font-mono text-white">Rp {{ number_format($totalTol, 0, ',', '.') }} ({{ $pctTol }}%)</span>
                    </div>
                    <div class="w-full h-2 rounded-full bg-slate-900 overflow-hidden">
                        <div class="h-full bg-sky-500 rounded-full" style="width: {{ $pctTol }}%"></div>
                    </div>
                </div>

                <!-- Parkir -->
                @php
                    $pctParkir = $totalSemuaPengeluaran > 0 ? round(($totalParkir / $totalSemuaPengeluaran) * 100, 1) : 0;
                @endphp
                <div class="space-y-1">
                    <div class="flex items-center justify-between font-semibold">
                        <span class="text-indigo-400 flex items-center gap-1.5"><span class="material-symbols-outlined text-sm">local_parking</span> Parkir</span>
                        <span class="font-mono text-white">Rp {{ number_format($totalParkir, 0, ',', '.') }} ({{ $pctParkir }}%)</span>
                    </div>
                    <div class="w-full h-2 rounded-full bg-slate-900 overflow-hidden">
                        <div class="h-full bg-indigo-500 rounded-full" style="width: {{ $pctParkir }}%"></div>
                    </div>
                </div>

                <!-- Servis -->
                @php
                    $pctServis = $totalSemuaPengeluaran > 0 ? round(($totalServisOps / $totalSemuaPengeluaran) * 100, 1) : 0;
                @endphp
                <div class="space-y-1">
                    <div class="flex items-center justify-between font-semibold">
                        <span class="text-rose-400 flex items-center gap-1.5"><span class="material-symbols-outlined text-sm">build</span> Servis & Bengkel</span>
                        <span class="font-mono text-white">Rp {{ number_format($totalServisOps, 0, ',', '.') }} ({{ $pctServis }}%)</span>
                    </div>
                    <div class="w-full h-2 rounded-full bg-slate-900 overflow-hidden">
                        <div class="h-full bg-rose-500 rounded-full" style="width: {{ $pctServis }}%"></div>
                    </div>
                </div>

                <!-- Lainnya -->
                @php
                    $pctLainnya = $totalSemuaPengeluaran > 0 ? round(($totalLainnya / $totalSemuaPengeluaran) * 100, 1) : 0;
                @endphp
                <div class="space-y-1">
                    <div class="flex items-center justify-between font-semibold">
                        <span class="text-slate-400 flex items-center gap-1.5"><span class="material-symbols-outlined text-sm">category</span> Lainnya</span>
                        <span class="font-mono text-white">Rp {{ number_format($totalLainnya, 0, ',', '.') }} ({{ $pctLainnya }}%)</span>
                    </div>
                    <div class="w-full h-2 rounded-full bg-slate-900 overflow-hidden">
                        <div class="h-full bg-slate-600 rounded-full" style="width: {{ $pctLainnya }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Col 2 & 3: Breakdown per Armada Kendaraan -->
        <div class="lg:col-span-2 glass-panel p-6 rounded-3xl space-y-4 border border-slate-800">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-sky-400">directions_car</span>
                    <h3 class="font-display font-bold text-white text-base">Rekap Pengeluaran per Armada Kendaraan</h3>
                </div>
                <span class="text-xs text-slate-400">Total: {{ count($expensesByVehicle) }} Armada</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs min-w-[600px]">
                    <thead>
                        <tr class="text-slate-400 border-b border-slate-800">
                            <th class="pb-3 font-semibold">Armada Mobil</th>
                            <th class="pb-3 font-semibold text-rose-400">Pengeluaran Ops</th>
                            <th class="pb-3 font-semibold text-indigo-400">Biaya Servis Bengkel</th>
                            <th class="pb-3 font-semibold text-amber-400 font-mono">Total Biaya</th>
                            <th class="pb-3 font-semibold text-right">% Kontribusi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 font-mono">
                        @forelse($expensesByVehicle as $item)
                        @php
                            $pctArmada = $totalSemuaPengeluaran > 0 ? round(($item['total'] / $totalSemuaPengeluaran) * 100, 1) : 0;
                        @endphp
                        <tr>
                            <td class="py-3 font-sans">
                                <span class="font-bold text-white block">{{ $item['vehicle']->plat_nomor }}</span>
                                <span class="text-[11px] text-slate-400">{{ $item['vehicle']->merk }}</span>
                            </td>
                            <td class="py-3 text-rose-400 font-semibold whitespace-nowrap">
                                Rp {{ number_format($item['ops'], 0, ',', '.') }}
                            </td>
                            <td class="py-3 text-indigo-400 font-semibold whitespace-nowrap">
                                Rp {{ number_format($item['servis'], 0, ',', '.') }}
                            </td>
                            <td class="py-3 text-amber-400 font-bold whitespace-nowrap">
                                Rp {{ number_format($item['total'], 0, ',', '.') }}
                            </td>
                            <td class="py-3 text-right font-sans">
                                <span class="px-2 py-0.5 rounded bg-slate-900 text-sky-400 border border-slate-800 font-bold text-[11px]">
                                    {{ $pctArmada }}%
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-500 font-sans">Belum ada pengeluaran khusus armada.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Rincian Transaksi Pengeluaran Ops Lengkap -->
    <div class="glass-panel p-6 rounded-3xl space-y-4 border border-slate-800">
        <div class="flex items-center justify-between pb-3 border-b border-slate-800">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-400">receipt_long</span>
                <h3 class="font-display font-bold text-white text-base">Rincian Transaksi Pengeluaran Operasional</h3>
            </div>
            <span class="text-xs text-slate-400">Total: {{ count($expenses) }} Transaksi</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs min-w-[650px]">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-800">
                        <th class="pb-3 font-semibold">Tanggal</th>
                        <th class="pb-3 font-semibold">Kategori</th>
                        <th class="pb-3 font-semibold text-rose-400">Nominal (Rp)</th>
                        <th class="pb-3 font-semibold">Rute & Armada Mobil</th>
                        <th class="pb-3 font-semibold">Catatan Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($expenses as $e)
                    <tr>
                        <td class="py-3 whitespace-nowrap">
                            <span class="font-bold text-white block">{{ $e->tanggal->format('d M Y') }}</span>
                        </td>
                        <td class="py-3">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                {{ $e->kategori === 'BBM' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : ($e->kategori === 'Tol' ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20' : ($e->kategori === 'Parkir' ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20')) }}">
                                {{ $e->kategori }}
                            </span>
                        </td>
                        <td class="py-3 font-mono font-bold text-rose-400 whitespace-nowrap">
                            Rp {{ number_format($e->jumlah, 0, ',', '.') }}
                        </td>
                        <td class="py-3">
                            @if($e->schedule)
                                <span class="font-semibold text-white block">{{ $e->schedule->rute }}</span>
                                <span class="text-[11px] text-slate-400">Mobil: {{ $e->schedule->vehicle->plat_nomor ?? '-' }} ({{ $e->schedule->driver->nama ?? '-' }})</span>
                            @else
                                <span class="text-slate-500 italic text-[11px]">Pengeluaran Umum / Operasional</span>
                            @endif
                        </td>
                        <td class="py-3 text-slate-300 max-w-xs truncate">
                            {{ $e->keterangan ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-500">Tidak ada transaksi pengeluaran pada periode ini.</td>
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
    function exportReportToPNG() {
        const captureElement = document.getElementById('reportCaptureArea');
        
        Swal.fire({
            title: 'Menyiapkan Laporan Image PNG...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        html2canvas(captureElement, {
            scale: 2,
            backgroundColor: '#020617',
            useCORS: true,
            ignoreElements: (element) => element.classList.contains('no-print')
        }).then(canvas => {
            const link = document.createElement('a');
            link.download = `Laporan_Pengeluaran_TravelManager_{{ date('Y-m-d') }}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
            
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Laporan Pengeluaran berhasil diunduh sebagai gambar PNG.',
                timer: 2000,
                showConfirmButton: false,
                background: '#0f172a',
                color: '#f8fafc'
            });
        }).catch(err => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal Export!',
                text: 'Terjadi kesalahan saat mengunduh laporan.',
                background: '#0f172a',
                color: '#f8fafc'
            });
        });
    }
</script>
@endsection
