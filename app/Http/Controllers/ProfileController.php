<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Driver;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isOwner()) {
            // Owner Profile Metrics & Info
            $totalVehicles = Vehicle::count();
            $totalDrivers = Driver::count();
            $totalBookings = Booking::count();

            return view('profile.owner', compact('user', 'totalVehicles', 'totalDrivers', 'totalBookings'));
        }

        // Supir Profile
        return redirect()->route('drivers.index');
    }

    public function updateOwner(Request $request)
    {
        $user = auth()->user();

        if (!$user->isOwner()) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'no_hp' => 'required|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'],
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('profile.index')->with('success', 'Profil Owner & nomor WA tujuan notifikasi berhasil diperbarui.');
    }
}
