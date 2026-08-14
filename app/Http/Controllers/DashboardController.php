<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Driver;
use App\Models\Expense;
use App\Models\Maintenance;
use App\Models\Schedule;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $user = auth()->user();

        // Common Counts & Data
        $allVehicles = Vehicle::all();
        $totalBookingHariIni = Schedule::whereDate('tanggal_keberangkatan', $today)->count() + Booking::whereDate('tanggal_berangkat', $today)->count();
        $totalPendapatanHariIni = Schedule::whereDate('tanggal_keberangkatan', $today)->sum('tarif') + Booking::whereDate('tanggal_berangkat', $today)->sum('tarif');
        
        $totalArmada = Vehicle::count();
        $armadaAktif = Vehicle::whereIn('status', ['Tersedia', 'Beroperasi'])->count();
        
        $totalSopir = Driver::count();
        $sopirAktif = Driver::whereIn('status_aktif', ['Aktif', 'Sedang Jalan'])->count();
        
        $kendaraanPerluServis = Vehicle::where('status', 'Servis')
            ->orWhereHas('maintenances', function($q) use ($today) {
                $q->where('tujuan_perawatan', 'Perbaikan');
            })
            ->distinct()
            ->take(10)
            ->get();

        $recentBookings = Booking::with('driver')->latest()->take(10)->get();
        $schedulesToday = Schedule::with(['vehicle', 'driver', 'driver2'])
            ->whereDate('tanggal_keberangkatan', $today)
            ->take(10)
            ->get();

        $totalPengeluaranHariIni = Expense::whereDate('tanggal', $today)->sum('jumlah');

        // Driver-specific schedule filtering
        $mySchedules = collect();
        $myDriverRecord = null;

        if ($user && $user->isSupir()) {
            if ($user->driver_id) {
                $myDriverRecord = Driver::find($user->driver_id);
            } else {
                $myDriverRecord = Driver::where('nama', 'LIKE', '%' . $user->name . '%')->first();
            }

            if ($myDriverRecord) {
                $mySchedules = Schedule::with(['vehicle', 'bookings', 'driver', 'driver2'])
                    ->where(function($q) use ($myDriverRecord) {
                        $q->where('driver_id', $myDriverRecord->id)
                          ->orWhere('driver_2_id', $myDriverRecord->id);
                    })
                    ->latest()
                    ->take(10)
                    ->get();
            }
        }

        return view('dashboard', compact(
            'allVehicles',
            'totalBookingHariIni',
            'totalPendapatanHariIni',
            'totalArmada',
            'armadaAktif',
            'totalSopir',
            'sopirAktif',
            'kendaraanPerluServis',
            'recentBookings',
            'schedulesToday',
            'totalPengeluaranHariIni',
            'mySchedules',
            'myDriverRecord'
        ));
    }
}
