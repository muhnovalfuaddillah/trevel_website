<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Driver;
use App\Models\Schedule;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Auto-sync driver and vehicle statuses for orphaned records
        $this->syncStatuses();

        if ($user && $user->isSupir()) {
            $driverId = $user->driver_id;
            if (!$driverId) {
                $matched = Driver::where('nama', 'LIKE', '%' . $user->name . '%')->first();
                $driverId = $matched->id ?? 0;
            }
            // Supir strictly sees ONLY their own assigned trip schedules
            $schedules = Schedule::where('driver_id', $driverId)->with(['vehicle', 'driver', 'bookings'])->withCount('bookings')->latest()->take(10)->get();
            $bookings = Booking::where('driver_id', $driverId)->with('driver')->latest()->take(10)->get();
        } else {
            // Owner sees all schedules
            $schedules = Schedule::with(['vehicle', 'driver', 'bookings'])->withCount('bookings')->latest()->take(10)->get();
            $bookings = Booking::with('driver')->latest()->take(10)->get();
        }

        // Only fetch ready/available vehicles (Tersedia) and drivers (Aktif)
        $readyVehicles = Vehicle::where('status', 'Tersedia')->get();
        $readyDrivers = Driver::where('status_aktif', 'Aktif')->get();

        // All vehicles and drivers for edit mode fallback
        $allVehicles = Vehicle::all();
        $allDrivers = Driver::all();

        return view('schedules.index', compact('schedules', 'bookings', 'readyVehicles', 'readyDrivers', 'allVehicles', 'allDrivers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_keberangkatan' => 'required|date',
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:drivers,id',
            'rute' => 'required|string|max:255',
            'status_perjalanan' => 'required|in:Terjadwal,Dalam Perjalanan,Selesai,Dibatalkan',
        ]);

        $schedule = Schedule::create($validated);

        if ($validated['status_perjalanan'] === 'Dalam Perjalanan') {
            Vehicle::where('id', $validated['vehicle_id'])->update(['status' => 'Beroperasi']);
            Driver::where('id', $validated['driver_id'])->update(['status_aktif' => 'Sedang Jalan']);
        } else {
            $this->syncStatuses();
        }

        return redirect()->route('schedules.index')->with('success', 'Jadwal perjalanan berhasil dibuat.');
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'tanggal_keberangkatan' => 'required|date',
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:drivers,id',
            'rute' => 'required|string|max:255',
            'status_perjalanan' => 'required|in:Terjadwal,Dalam Perjalanan,Selesai,Dibatalkan',
        ]);

        $schedule->update($validated);

        if ($validated['status_perjalanan'] === 'Dalam Perjalanan') {
            Vehicle::where('id', $validated['vehicle_id'])->update(['status' => 'Beroperasi']);
            Driver::where('id', $validated['driver_id'])->update(['status_aktif' => 'Sedang Jalan']);
        }

        $this->syncStatuses();

        return redirect()->route('schedules.index')->with('success', 'Jadwal perjalanan berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        $this->syncStatuses();

        return redirect()->route('schedules.index')->with('success', 'Jadwal perjalanan berhasil dihapus dan status sopir/armada telah dikembalikan ke Aktif/Tersedia.');
    }

    private function syncStatuses()
    {
        $activeVehicleIds = Schedule::where('status_perjalanan', 'Dalam Perjalanan')->pluck('vehicle_id')->toArray();
        $activeDriverIds = Schedule::where('status_perjalanan', 'Dalam Perjalanan')->pluck('driver_id')->toArray();

        Driver::where('status_aktif', 'Sedang Jalan')
            ->whereNotIn('id', $activeDriverIds)
            ->update(['status_aktif' => 'Aktif']);

        Vehicle::where('status', 'Beroperasi')
            ->whereNotIn('id', $activeVehicleIds)
            ->update(['status' => 'Tersedia']);
    }
}
