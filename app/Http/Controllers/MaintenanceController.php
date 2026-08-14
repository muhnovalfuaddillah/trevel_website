<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Maintenance;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $vehicles = Vehicle::with(['schedules' => function($q) {
            $q->with('driver')->latest('tanggal_keberangkatan');
        }])->get();
        $drivers = Driver::all();

        if ($user->isSupir()) {
            $driverRecord = Driver::find($user->driver_id) ?? Driver::where('nama', 'LIKE', '%' . $user->name . '%')->first();
            $driverName = $driverRecord->nama ?? $user->name;
            $driverId = $driverRecord->id ?? 0;

            $driverVehicleIds = \App\Models\Schedule::where('driver_id', $driverId)
                ->orWhere('driver_2_id', $driverId)
                ->pluck('vehicle_id')
                ->unique();

            $maintenances = Maintenance::with('vehicle')
                ->where(function($q) use ($driverName, $driverVehicleIds) {
                    $q->where('petugas_perawatan', 'LIKE', '%' . $driverName . '%')
                      ->orWhereIn('vehicle_id', $driverVehicleIds);
                })
                ->latest('tanggal_perawatan')
                ->take(10)
                ->get();
        } else {
            $maintenances = Maintenance::with('vehicle')->latest('tanggal_perawatan')->take(10)->get();
        }

        return view('maintenances.index', compact('maintenances', 'vehicles', 'drivers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'tanggal_perawatan' => 'required|date',
            'jenis_perawatan' => 'required|array|min:1',
            'jenis_perawatan.*' => 'string|in:Ganti Oli,Servis Mesin,Ganti Ban,Servis AC,Rem & Kampas,Perawatan Body,Lainnya,Ganti oli,Servis mesin,Ganti ban',
            'tujuan_perawatan' => 'required|in:Rutin,Perbaikan',
            'biaya' => 'required|numeric|min:0',
            'kilometer' => 'nullable|numeric|min:0',
            'petugas_perawatan' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
            'foto_bukti.*' => 'nullable|image|mimes:jpg,jpeg,png,webp,heic|max:5120',
        ]);

        $photos = [];
        if ($request->hasFile('foto_bukti')) {
            $uploadDir = public_path('uploads/maintenances');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            foreach ($request->file('foto_bukti') as $file) {
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($uploadDir, $fileName);
                $photos[] = '/uploads/maintenances/' . $fileName;
            }
        }
        $validated['foto_bukti'] = $photos;

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
            'jenis_perawatan.*' => 'string|in:Ganti Oli,Servis Mesin,Ganti Ban,Servis AC,Rem & Kampas,Perawatan Body,Lainnya,Ganti oli,Servis mesin,Ganti ban',
            'tujuan_perawatan' => 'required|in:Rutin,Perbaikan',
            'biaya' => 'required|numeric|min:0',
            'kilometer' => 'nullable|numeric|min:0',
            'petugas_perawatan' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
            'foto_bukti.*' => 'nullable|image|mimes:jpg,jpeg,png,webp,heic|max:5120',
        ]);

        $photos = $maintenance->foto_bukti ?? [];
        if ($request->hasFile('foto_bukti')) {
            $uploadDir = public_path('uploads/maintenances');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            foreach ($request->file('foto_bukti') as $file) {
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($uploadDir, $fileName);
                $photos[] = '/uploads/maintenances/' . $fileName;
            }
        }
        $validated['foto_bukti'] = $photos;

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
