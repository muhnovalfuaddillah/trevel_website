<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        if (!auth()->user() || !auth()->user()->isOwner()) {
            return redirect()->route('dashboard')->with('error', 'Akses Ditolak! Fitur Pengeluaran Operasional hanya dapat diakses oleh Owner.');
        }

        // Limit to 10 newest operational expense records
        $expenses = Expense::with('schedule')->latest('tanggal')->take(10)->get();
        $schedules = Schedule::with('vehicle')->latest()->take(10)->get();

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
