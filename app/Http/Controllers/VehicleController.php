<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        // Auto-sync vehicle status: reset to 'Tersedia' if not in any active trip ('Dalam Perjalanan')
        $activeVehicleIds = Schedule::where('status_perjalanan', 'Dalam Perjalanan')->pluck('vehicle_id')->toArray();
        Vehicle::where('status', 'Beroperasi')
            ->whereNotIn('id', $activeVehicleIds)
            ->update(['status' => 'Tersedia']);

        // Limit to 10 newest vehicles
        $vehicles = Vehicle::latest()->take(10)->get();
        return view('vehicles.index', compact('vehicles'));
    }

    public function store(Request $request)
    {
        if (!auth()->user() || !auth()->user()->isOwner()) {
            return redirect()->route('vehicles.index')->with('error', 'Akses Ditolak! Hanya Owner yang dapat menambahkan kendaraan armada.');
        }

        $validated = $request->validate([
            'plat_nomor' => 'required|string|max:20|unique:vehicles',
            'merk' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
            'status' => 'required|in:Tersedia,Beroperasi,Servis',
        ]);

        $validated['tarif_per_hari'] = 0;

        Vehicle::create($validated);

        return redirect()->route('vehicles.index')->with('success', 'Kendaraan armada berhasil ditambahkan.');
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        if (!auth()->user() || !auth()->user()->isOwner()) {
            return redirect()->route('vehicles.index')->with('error', 'Akses Ditolak! Hanya Owner yang dapat memperbarui data kendaraan.');
        }

        $validated = $request->validate([
            'plat_nomor' => 'required|string|max:20|unique:vehicles,plat_nomor,' . $vehicle->id,
            'merk' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
            'status' => 'required|in:Tersedia,Beroperasi,Servis',
        ]);

        $vehicle->update($validated);

        return redirect()->route('vehicles.index')->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    public function destroy(Vehicle $vehicle)
    {
        if (!auth()->user() || !auth()->user()->isOwner()) {
            return redirect()->route('vehicles.index')->with('error', 'Akses Ditolak! Hanya Owner yang dapat menghapus kendaraan.');
        }

        $vehicle->delete();
        return redirect()->route('vehicles.index')->with('success', 'Kendaraan berhasil dihapus.');
    }
}
