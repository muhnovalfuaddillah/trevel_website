<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
    {
        if (!auth()->user() || !auth()->user()->isOwner()) {
            return redirect()->route('dashboard')->with('error', 'Akses Ditolak! Fitur Perawatan & Servis Kendaraan hanya dapat diakses oleh Owner.');
        }

        // Limit to 10 newest maintenance records
        $maintenances = Maintenance::with('vehicle')->latest('tanggal_perawatan')->take(10)->get();
        $vehicles = Vehicle::all();

        return view('maintenances.index', compact('maintenances', 'vehicles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'tanggal_perawatan' => 'required|date',
            'jenis_perawatan' => 'required|array|min:1',
            'jenis_perawatan.*' => 'string|in:Ganti oli,Servis mesin,Ganti ban,Servis AC,Lainnya',
            'tujuan_perawatan' => 'required|in:Rutin,Perbaikan',
            'biaya' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        Maintenance::create($validated);

        if ($validated['tujuan_perawatan'] === 'Perbaikan') {
            Vehicle::where('id', $validated['vehicle_id'])->update(['status' => 'Servis']);
        }

        return redirect()->route('maintenances.index')->with('success', 'Catatan perawatan kendaraan berhasil disimpan.');
    }

    public function update(Request $request, Maintenance $maintenance)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'tanggal_perawatan' => 'required|date',
            'jenis_perawatan' => 'required|array|min:1',
            'jenis_perawatan.*' => 'string|in:Ganti oli,Servis mesin,Ganti ban,Servis AC,Lainnya',
            'tujuan_perawatan' => 'required|in:Rutin,Perbaikan',
            'biaya' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        $maintenance->update($validated);

        if ($validated['tujuan_perawatan'] === 'Perbaikan') {
            Vehicle::where('id', $validated['vehicle_id'])->update(['status' => 'Servis']);
        }

        return redirect()->route('maintenances.index')->with('success', 'Data perawatan kendaraan berhasil diperbarui.');
    }

    public function destroy(Maintenance $maintenance)
    {
        $maintenance->delete();
        return redirect()->route('maintenances.index')->with('success', 'Catatan perawatan berhasil dihapus.');
    }
}
