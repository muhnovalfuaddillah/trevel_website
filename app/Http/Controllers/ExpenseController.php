<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->isSupir()) {
            $driverRecord = \App\Models\Driver::find($user->driver_id) ?? \App\Models\Driver::where('nama', 'LIKE', '%' . $user->name . '%')->first();
            $driverId = $driverRecord->id ?? 0;

            $driverScheduleIds = Schedule::where('driver_id', $driverId)
                ->orWhere('driver_2_id', $driverId)
                ->pluck('id');

            $expenses = Expense::with('schedule.vehicle')
                ->whereIn('schedule_id', $driverScheduleIds)
                ->latest('tanggal')
                ->take(10)
                ->get();

            $schedules = Schedule::with('vehicle')
                ->where(function($q) use ($driverId) {
                    $q->where('driver_id', $driverId)->orWhere('driver_2_id', $driverId);
                })
                ->latest()
                ->take(10)
                ->get();
        } else {
            $expenses = Expense::with('schedule.vehicle')->latest('tanggal')->take(10)->get();
            $schedules = Schedule::with('vehicle')->latest()->take(10)->get();
        }

        return view('expenses.index', compact('expenses', 'schedules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|in:BBM,Tol,Parkir,Servis kendaraan,Lainnya',
            'jumlah' => 'required|numeric|min:0',
            'schedule_id' => 'nullable|exists:schedules,id',
            'keterangan' => 'nullable|string',
        ]);

        Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'Catatan pengeluaran berhasil disimpan.');
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|in:BBM,Tol,Parkir,Servis kendaraan,Lainnya',
            'jumlah' => 'required|numeric|min:0',
            'schedule_id' => 'nullable|exists:schedules,id',
            'keterangan' => 'nullable|string',
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')->with('success', 'Catatan pengeluaran berhasil diperbarui.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Catatan pengeluaran berhasil dihapus.');
    }
}
