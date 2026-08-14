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

    <!-- Sub-Nav Tab Switcher (Cashflow vs Laporan Pengeluaran) -->
    <div class="flex items-center justify-between gap-4 border-b border-slate-800 pb-4 no-print">
        <div class="flex items-center gap-2">
            <a href="{{ route('reports.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-sky-400 bg-sky-600/20 border border-sky-500/30 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">payments</span>
                Laporan Keuangan & Cashflow
            </a>
            <a href="{{ route('reports.expenses') }}" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-400 hover:text-white hover:bg-slate-800/60 transition-all flex items-center gap-2">
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
                <h2 class="text-xl font-display font-black text-white tracking-wide">Filter Periode Cashflow & Laporan</h2>
                <p class="text-xs text-slate-400 mt-0.5">Pilih skema laporan: Semua Data, Per Hari, Per Bulan, atau Per Tahun.</p>
            </div>

            <div class="flex flex-wrap items-center gap-1.5 bg-slate-900 p-1.5 rounded-2xl border border-slate-800 text-xs font-semibold no-print">
                <a href="{{ route('reports.index', ['period_type' => 'semua', 'vehicle_id' => $selectedVehicleId]) }}"
                   class="px-4 py-2 rounded-xl transition-all flex items-center gap-1.5 {{ $periodType === 'semua' ? 'bg-gradient-to-r from-sky-600 to-indigo-600 text-white font-bold shadow-lg shadow-sky-600/30' : 'text-slate-400 hover:text-white' }}">
                    <span class="material-symbols-outlined text-sm">database</span> Semua Data
                </a>

                <a href="{{ route('reports.index', ['period_type' => 'harian', 'start_date' => $startDate, 'end_date' => $endDate, 'vehicle_id' => $selectedVehicleId]) }}"
                   class="px-4 py-2 rounded-xl transition-all flex items-center gap-1.5 {{ $periodType === 'harian' ? 'bg-gradient-to-r from-sky-600 to-indigo-600 text-white font-bold shadow-lg shadow-sky-600/30' : 'text-slate-400 hover:text-white' }}">
                    <span class="material-symbols-outlined text-sm">calendar_view_day</span> Per Hari
                </a>

                <a href="{{ route('reports.index', ['period_type' => 'bulanan', 'year' => $selectedYear, 'vehicle_id' => $selectedVehicleId]) }}"
                   class="px-4 py-2 rounded-xl transition-all flex items-center gap-1.5 {{ $periodType === 'bulanan' ? 'bg-gradient-to-r from-sky-600 to-indigo-600 text-white font-bold shadow-lg shadow-sky-600/30' : 'text-slate-400 hover:text-white' }}">
                    <span class="material-symbols-outlined text-sm">calendar_view_month</span> Per Bulan
                </a>

                <a href="{{ route('reports.index', ['period_type' => 'tahunan', 'vehicle_id' => $selectedVehicleId]) }}"
                   class="px-4 py-2 rounded-xl transition-all flex items-center gap-1.5 {{ $periodType === 'tahunan' ? 'bg-gradient-to-r from-sky-600 to-indigo-600 text-white font-bold shadow-lg shadow-sky-600/30' : 'text-slate-400 hover:text-white' }}">
                    <span class="material-symbols-outlined text-sm">date_range</span> Per Tahun
                </a>
            </div>
        </div>

        <!-- Filter Inputs Form & Export Action Buttons -->
        <form method="GET" action="{{ route('reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
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
                <div>
                    <span class="text-xs text-slate-400 block pb-2">Menampilkan 12 bulan pada tahun {{ $selectedYear }}</span>
                </div>
            @elseif($periodType === 'tahunan')
                <div>
                    <span class="text-xs text-slate-400 block pb-2">Menampilkan rekapitulasi performa per tahunan</span>
                </div>
            @else
                <div>
                    <span class="text-xs text-emerald-400 font-semibold block pb-2">✔ Menampilkan akumulasi seluruh data database</span>
                </div>
            @endif

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Filter Armada Kendaraan</label>
                <select name="vehicle_id" class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-900 border border-slate-700 text-xs text-white focus:outline-none focus:border-sky-500 font-semibold">
                    <option value="">-- Semua Kendaraan Armada --</option>
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

        <!-- Card 1: Total Dana Masuk -->
        <div class="glass-card p-6 rounded-3xl border border-emerald-500/30 relative overflow-hidden flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Omzet / Dana Masuk</span>
                <div class="w-10 h-10 rounded-2xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center border border-emerald-500/20">
                    <span class="material-symbols-outlined text-xl">trending_up</span>
                </div>
            </div>
            <div>
                <h3 class="font-display font-black text-2xl text-emerald-400 font-mono">Rp {{ number_format($totalDanaMasuk, 0, ',', '.') }}</h3>
                <p class="text-[11px] text-slate-400 mt-1">Akumulasi penerimaan tarif sewa booking travel</p>
            </div>
        </div>

        <!-- Card 2: Total Pengeluaran Operasional & Bengkel -->
        <div class="glass-card p-6 rounded-3xl border border-rose-500/30 relative overflow-hidden flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Pengeluaran & Servis</span>
                <div class="w-10 h-10 rounded-2xl bg-rose-500/10 text-rose-400 flex items-center justify-center border border-rose-500/20">
                    <span class="material-symbols-outlined text-xl">trending_down</span>
                </div>
            </div>
            <div>
                <h3 class="font-display font-black text-2xl text-rose-400 font-mono">Rp {{ number_format($totalDanaKeluar, 0, ',', '.') }}</h3>
                <p class="text-[11px] text-slate-400 mt-1">Ops: Rp {{ number_format($totalPengeluaranOperasional, 0, ',', '.') }} | Servis: Rp {{ number_format($totalBiayaMaintenance, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Card 3: Laba / Rugi Bersih -->
        <div class="glass-card p-6 rounded-3xl border border-sky-500/30 relative overflow-hidden flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Laba / Rugi Net Bersih</span>
                <div class="w-10 h-10 rounded-2xl bg-sky-500/10 text-sky-400 flex items-center justify-center border border-sky-500/20">
                    <span class="material-symbols-outlined text-xl">account_balance_wallet</span>
                </div>
            </div>
            <div>
                <h3 class="font-display font-black text-2xl font-mono {{ $labaRugiSederhana >= 0 ? 'text-sky-400' : 'text-rose-400' }}">
                    Rp {{ number_format($labaRugiSederhana, 0, ',', '.') }}
                </h3>
                <p class="text-[11px] text-slate-400 mt-1">Estimasi keutungan bersih setelah beban biaya</p>
            </div>
        </div>

        <!-- Card 4: Sisa Pelunasan Kurang Bayar -->
        <div class="glass-card p-6 rounded-3xl border border-amber-500/30 relative overflow-hidden flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Piutang Pelunasan (Belum Lunas)</span>
                <div class="w-10 h-10 rounded-2xl bg-amber-500/10 text-amber-400 flex items-center justify-center border border-amber-500/20">
                    <span class="material-symbols-outlined text-xl">pending_actions</span>
                </div>
            </div>
            <div>
                <h3 class="font-display font-black text-2xl text-amber-400 font-mono">Rp {{ number_format($totalSisaPelunasan, 0, ',', '.') }}</h3>
                <p class="text-[11px] text-slate-400 mt-1">Total piutang sisa pembayaran status DP</p>
            </div>
        </div>

    </div>

    <!-- Table Cashflow Time Series Breakdown -->
    <div class="glass-panel rounded-3xl p-6 border border-slate-800 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <div>
                <h3 class="font-display font-bold text-base text-white">Rekapitulasi Cashflow Per Periode ({{ strtoupper($periodType) }})</h3>
                <p class="text-xs text-slate-400">Ringkasan perbandingan omzet dana masuk vs pengeluaran operasional & servis bengkel.</p>
            </div>
            <span class="text-xs text-slate-400 font-semibold bg-slate-900 px-3 py-1 rounded-full border border-slate-800">
                Data Terurut Terbaru
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs min-w-[650px]">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-800">
                        <th class="pb-3.5 font-semibold">Periode Tanggal</th>
                        <th class="pb-3.5 font-semibold text-emerald-400">Dana Masuk (Booking)</th>
                        <th class="pb-3.5 font-semibold text-rose-400">Pengeluaran Ops</th>
                        <th class="pb-3.5 font-semibold text-rose-400">Biaya Servis/Bengkel</th>
                        <th class="pb-3.5 font-semibold text-sky-400">Net Saldo Bersih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 font-mono">
                    @forelse($summaryData as $row)
                    @php
                        $netRow = $row['dana_masuk'] - ($row['pengeluaran_ops'] + $row['biaya_maintenance']);
                    @endphp
                    <tr class="hover:bg-slate-900/40 transition-colors">
                        <td class="py-3.5 font-sans font-bold text-white whitespace-nowrap">
                            {{ $row['label'] }}
                        </td>
                        <td class="py-3.5 font-bold text-emerald-400 whitespace-nowrap">
                            Rp {{ number_format($row['dana_masuk'], 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 text-rose-400 whitespace-nowrap">
                            Rp {{ number_format($row['pengeluaran_ops'], 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 text-rose-400 whitespace-nowrap">
                            Rp {{ number_format($row['biaya_maintenance'], 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 font-bold {{ $netRow >= 0 ? 'text-sky-400' : 'text-rose-400' }} whitespace-nowrap">
                            Rp {{ number_format($netRow, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-500 font-sans">Tidak ada rekapitulasi transaksi pada periode ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 3 Detailed Transaction Tables (Dana Masuk, Pengeluaran Ops, Biaya Bengkel) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Col 1: Transaksi Dana Masuk Booking Terbaru -->
        <div class="glass-panel p-6 rounded-3xl space-y-4 border border-slate-800">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <span class="font-display font-bold text-sm text-emerald-400 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-base">arrow_downward</span> Rincian Dana Masuk
                </span>
                <span class="text-[10px] text-slate-400 font-mono">{{ count($detailDanaMasuk) }} Tx</span>
            </div>
            <div class="space-y-3 text-xs">
                @forelse($detailDanaMasuk as $bm)
                <div class="p-3 rounded-2xl bg-slate-900/80 border border-slate-800/80 space-y-1">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-white">{{ $bm->asal }} ➔ {{ $bm->tujuan }}</span>
                        <span class="font-mono font-bold text-emerald-400">Rp {{ number_format($bm->tarif, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-slate-400">
                        <span>Mobil: {{ $bm->vehicle->plat_nomor ?? '-' }}</span>
                        <span>{{ $bm->tanggal_berangkat->format('d M Y') }}</span>
                    </div>
                </div>
                @empty
                <p class="text-center text-slate-500 text-xs py-4">Belum ada transaksi dana masuk.</p>
                @endforelse
            </div>
        </div>

        <!-- Col 2: Transaksi Pengeluaran Operasional Terbaru -->
        <div class="glass-panel p-6 rounded-3xl space-y-4 border border-slate-800">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <span class="font-display font-bold text-sm text-rose-400 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-base">arrow_upward</span> Rincian Pengeluaran Ops
                </span>
                <span class="text-[10px] text-slate-400 font-mono">{{ count($detailDanaKeluarOps) }} Tx</span>
            </div>
            <div class="space-y-3 text-xs">
                @forelse($detailDanaKeluarOps as $eo)
                <div class="p-3 rounded-2xl bg-slate-900/80 border border-slate-800/80 space-y-1">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-white">{{ $eo->kategori }}</span>
                        <span class="font-mono font-bold text-rose-400">Rp {{ number_format($eo->jumlah, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-slate-400">
                        <span class="truncate max-w-[150px]">{{ $eo->keterangan ?? 'Pengeluaran ops' }}</span>
                        <span>{{ $eo->tanggal->format('d M Y') }}</span>
                    </div>
                </div>
                @empty
                <p class="text-center text-slate-500 text-xs py-4">Belum ada pengeluaran ops.</p>
                @endforelse
            </div>
        </div>

        <!-- Col 3: Transaksi Maintenance / Bengkel Terbaru -->
        <div class="glass-panel p-6 rounded-3xl space-y-4 border border-slate-800">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <span class="font-display font-bold text-sm text-indigo-400 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-base">build</span> Biaya Servis & Bengkel
                </span>
                <span class="text-[10px] text-slate-400 font-mono">{{ count($detailDanaKeluarSrv) }} Tx</span>
            </div>
            <div class="space-y-3 text-xs">
                @forelse($detailDanaKeluarSrv as $ms)
                <div class="p-3 rounded-2xl bg-slate-900/80 border border-slate-800/80 space-y-1">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-white">{{ $ms->vehicle->plat_nomor ?? '-' }} ({{ $ms->tujuan_perawatan }})</span>
                        <span class="font-mono font-bold text-indigo-400">Rp {{ number_format($ms->biaya, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-slate-400">
                        <span>{{ is_array($ms->jenis_perawatan) ? implode(', ', $ms->jenis_perawatan) : $ms->jenis_perawatan }}</span>
                        <span>{{ $ms->tanggal_perawatan->format('d M Y') }}</span>
                    </div>
                </div>
                @empty
                <p class="text-center text-slate-500 text-xs py-4">Belum ada servis bengkel.</p>
                @endforelse
            </div>
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
            link.download = `Laporan_Keuangan_TravelManager_{{ date('Y-m-d') }}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
            
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Laporan Keuangan berhasil diunduh sebagai gambar PNG.',
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
