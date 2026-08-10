<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Expense;
use App\Models\Maintenance;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user() || !auth()->user()->isOwner()) {
            return redirect()->route('dashboard')->with('error', 'Akses Ditolak! Fitur Laporan Keuangan & Laba Rugi hanya dapat diakses oleh Owner.');
        }

        $periodType = $request->input('period_type', 'semua'); // semua, harian, bulanan, tahunan
        $selectedYear = (int) $request->input('year', Carbon::now()->year);
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $selectedVehicleId = $request->input('vehicle_id');

        $vehicles = Vehicle::all();

        // 1. Build Base Queries
        $bookingsQuery = Booking::with(['driver', 'vehicle']);
        $expensesQuery = Expense::with('schedule.vehicle');
        $maintenancesQuery = Maintenance::with('vehicle');

        if ($selectedVehicleId) {
            $bookingsQuery->where('vehicle_id', $selectedVehicleId);
            $expensesQuery->whereHas('schedule', function ($q) use ($selectedVehicleId) {
                $q->where('vehicle_id', $selectedVehicleId);
            });
            $maintenancesQuery->where('vehicle_id', $selectedVehicleId);
        }

        if ($periodType === 'harian') {
            $bookingsQuery->whereBetween('tanggal_berangkat', [$startDate, $endDate]);
            $expensesQuery->whereBetween('tanggal', [$startDate, $endDate]);
            $maintenancesQuery->whereBetween('tanggal_perawatan', [$startDate, $endDate]);
        } elseif ($periodType === 'bulanan') {
            $bookingsQuery->whereYear('tanggal_berangkat', $selectedYear);
            $expensesQuery->whereYear('tanggal', $selectedYear);
            $maintenancesQuery->whereYear('tanggal_perawatan', $selectedYear);
        }

        $allBookings = $bookingsQuery->get();
        $allExpenses = $expensesQuery->get();
        $allMaintenances = $maintenancesQuery->get();

        // Exact Calculations
        $totalDanaMasuk = $allBookings->sum('tarif');
        $totalPengeluaranOperasional = $allExpenses->sum('jumlah');
        $totalBiayaMaintenance = $allMaintenances->sum('biaya');
        $totalDanaKeluar = $totalPengeluaranOperasional + $totalBiayaMaintenance;
        $labaRugiSederhana = $totalDanaMasuk - $totalDanaKeluar;

        // Calculate Outstanding Unpaid Amount (Total Piutang Kurang Bayar)
        $totalSisaPelunasan = $allBookings->sum(function ($b) {
            return $b->status_pembayaran === 'Lunas' ? 0 : max(0, $b->tarif - $b->harga_dp);
        });

        // Group expenses by category
        $expensesByCategory = $allExpenses->groupBy('kategori')->map(fn($item) => $item->sum('jumlah'));

        // 2. Detailed Transactions (Top 10 Newest for Each Category)
        $detailDanaMasuk = $allBookings->sortByDesc('tanggal_berangkat')->take(10);
        $detailDanaKeluarOps = $allExpenses->sortByDesc('tanggal')->take(10);
        $detailDanaKeluarSrv = $allMaintenances->sortByDesc('tanggal_perawatan')->take(10);

        // 3. Build Time-Series Cashflow Breakdown Data
        $cashflowData = [];

        if ($periodType === 'harian') {
            $period = CarbonPeriod::create($startDate, $endDate);
            foreach ($period as $date) {
                $dateStr = $date->format('Y-m-d');
                
                $masuk = $allBookings->filter(fn($b) => $b->tanggal_berangkat->format('Y-m-d') === $dateStr)->sum('tarif');
                $keluarOps = $allExpenses->filter(fn($e) => $e->tanggal->format('Y-m-d') === $dateStr)->sum('jumlah');
                $keluarSrv = $allMaintenances->filter(fn($m) => $m->tanggal_perawatan->format('Y-m-d') === $dateStr)->sum('biaya');
                $totalKeluar = $keluarOps + $keluarSrv;

                $cashflowData[] = [
                    'label' => $date->format('d M Y'),
                    'sublabel' => $date->translatedFormat('l'),
                    'dana_masuk' => $masuk,
                    'dana_keluar_ops' => $keluarOps,
                    'dana_keluar_srv' => $keluarSrv,
                    'total_dana_keluar' => $totalKeluar,
                    'saldo' => $masuk - $totalKeluar,
                ];
            }
            $cashflowData = array_slice(array_reverse($cashflowData), 0, 10);
        } elseif ($periodType === 'bulanan') {
            for ($m = 1; $m <= 12; $m++) {
                $monthDate = Carbon::create($selectedYear, $m, 1);
                $monthStr = $monthDate->format('Y-m');

                $masuk = $allBookings->filter(fn($b) => $b->tanggal_berangkat->format('Y-m') === $monthStr)->sum('tarif');
                $keluarOps = $allExpenses->filter(fn($e) => $e->tanggal->format('Y-m') === $monthStr)->sum('jumlah');
                $keluarSrv = $allMaintenances->filter(fn($m) => $m->tanggal_perawatan->format('Y-m') === $monthStr)->sum('biaya');
                $totalKeluar = $keluarOps + $keluarSrv;

                $cashflowData[] = [
                    'label' => $monthDate->translatedFormat('F Y'),
                    'sublabel' => 'Bulan ' . $m,
                    'dana_masuk' => $masuk,
                    'dana_keluar_ops' => $keluarOps,
                    'dana_keluar_srv' => $keluarSrv,
                    'total_dana_keluar' => $totalKeluar,
                    'saldo' => $masuk - $totalKeluar,
                ];
            }
        } elseif ($periodType === 'tahunan') {
            $years = range(Carbon::now()->year - 3, Carbon::now()->year + 1);
            foreach ($years as $yr) {
                $masuk = Booking::whereYear('tanggal_berangkat', $yr)->sum('tarif');
                $keluarOps = Expense::whereYear('tanggal', $yr)->sum('jumlah');
                $keluarSrv = Maintenance::whereYear('tanggal_perawatan', $yr)->sum('biaya');
                $totalKeluar = $keluarOps + $keluarSrv;

                $cashflowData[] = [
                    'label' => 'Tahun ' . $yr,
                    'sublabel' => 'Rekapitulasi ' . $yr,
                    'dana_masuk' => $masuk,
                    'dana_keluar_ops' => $keluarOps,
                    'dana_keluar_srv' => $keluarSrv,
                    'total_dana_keluar' => $totalKeluar,
                    'saldo' => $masuk - $totalKeluar,
                ];
            }
        } else {
            $allDates = collect()
                ->merge($allBookings->pluck('tanggal_berangkat')->map->format('Y-m-d'))
                ->merge($allExpenses->pluck('tanggal')->map->format('Y-m-d'))
                ->merge($allMaintenances->pluck('tanggal_perawatan')->map->format('Y-m-d'))
                ->unique()
                ->sortDesc()
                ->take(10);

            foreach ($allDates as $dateStr) {
                $cDate = Carbon::parse($dateStr);
                $masuk = $allBookings->filter(fn($b) => $b->tanggal_berangkat->format('Y-m-d') === $dateStr)->sum('tarif');
                $keluarOps = $allExpenses->filter(fn($e) => $e->tanggal->format('Y-m-d') === $dateStr)->sum('jumlah');
                $keluarSrv = $allMaintenances->filter(fn($m) => $m->tanggal_perawatan->format('Y-m-d') === $dateStr)->sum('biaya');
                $totalKeluar = $keluarOps + $keluarSrv;

                $cashflowData[] = [
                    'label' => $cDate->format('d M Y'),
                    'sublabel' => $cDate->translatedFormat('l'),
                    'dana_masuk' => $masuk,
                    'dana_keluar_ops' => $keluarOps,
                    'dana_keluar_srv' => $keluarSrv,
                    'total_dana_keluar' => $totalKeluar,
                    'saldo' => $masuk - $totalKeluar,
                ];
            }
        }

        return view('reports.index', compact(
            'periodType',
            'selectedYear',
            'startDate',
            'endDate',
            'selectedVehicleId',
            'vehicles',
            'totalDanaMasuk',
            'totalPengeluaranOperasional',
            'totalBiayaMaintenance',
            'totalDanaKeluar',
            'totalSisaPelunasan',
            'labaRugiSederhana',
            'expensesByCategory',
            'cashflowData',
            'detailDanaMasuk',
            'detailDanaKeluarOps',
            'detailDanaKeluarSrv'
        ));
    }
}
