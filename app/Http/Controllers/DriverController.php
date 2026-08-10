<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Schedule;
use App\Models\User;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Auto-sync driver status: reset to 'Aktif' if not in any active trip ('Dalam Perjalanan')
        $activeDriverIds = Schedule::where('status_perjalanan', 'Dalam Perjalanan')->pluck('driver_id')->toArray();
        Driver::where('status_aktif', 'Sedang Jalan')
            ->whereNotIn('id', $activeDriverIds)
            ->update(['status_aktif' => 'Aktif']);

        $myDriverRecord = null;
        $mySchedulesHistory = collect();

        if ($user && $user->isSupir()) {
            // Supir strictly sees ONLY their own driver record
            $driverId = $user->driver_id;
            if (!$driverId) {
                $matched = Driver::where('nama', 'LIKE', '%' . $user->name . '%')->first();
                $driverId = $matched->id ?? 0;
            }

            $myDriverRecord = Driver::with(['user'])->withCount(['schedules', 'bookings'])->find($driverId);
            if ($myDriverRecord) {
                $mySchedulesHistory = Schedule::with(['vehicle', 'bookings'])
                    ->where('driver_id', $myDriverRecord->id)
                    ->latest()
                    ->take(10)
                    ->get();
            }

            $drivers = $myDriverRecord ? collect([$myDriverRecord]) : collect();
        } else {
            // Owner sees all drivers (Limit 10) with linked user account credentials
            $drivers = Driver::with(['user'])->withCount(['schedules', 'bookings'])->latest()->take(10)->get();
        }

        return view('drivers.index', compact('drivers', 'myDriverRecord', 'mySchedulesHistory'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:20',
            'nomor_sim' => 'required|string|max:50',
            'status_aktif' => 'required|in:Aktif,Nonaktif,Sedang Jalan',
            'password' => 'nullable|string|min:4',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'foto_sim' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        if ($request->hasFile('foto_profil')) {
            $validated['foto_profil'] = $request->file('foto_profil')->store('drivers', 'public');
        }
        if ($request->hasFile('foto_ktp')) {
            $validated['foto_ktp'] = $request->file('foto_ktp')->store('drivers', 'public');
        }
        if ($request->hasFile('foto_sim')) {
            $validated['foto_sim'] = $request->file('foto_sim')->store('drivers', 'public');
        }

        if (isset($validated['foto_ktp']) || isset($validated['foto_sim'])) {
            $validated['status_verifikasi'] = 'Menunggu Verifikasi';
        }

        $plainPass = $request->input('password') ?: 'password';
        unset($validated['password']);

        $driver = Driver::create($validated);

        // Auto-create login user account for new driver using Phone Number
        User::create([
            'name' => $driver->nama,
            'email' => strtolower(str_replace(' ', '', $driver->nama)) . '@travel.com',
            'no_hp' => $driver->nomor_hp,
            'password' => Hash::make($plainPass),
            'password_hint' => $plainPass,
            'role' => 'supir',
            'driver_id' => $driver->id,
        ]);

        return redirect()->route('drivers.index')->with('success', 'Sopir baru & akun login Nomor HP berhasil dibuat.');
    }

    public function update(Request $request, Driver $driver)
    {
        $user = auth()->user();

        // Security check: Supir can only update their own profile
        if ($user && $user->isSupir()) {
            if ($user->driver_id && $user->driver_id != $driver->id) {
                return redirect()->route('drivers.index')->with('error', 'Akses Ditolak! Anda hanya dapat mengubah profil Anda sendiri.');
            }
        }

        $rules = [
            'nama' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:20',
            'nomor_sim' => 'required|string|max:50',
            'password' => 'nullable|string|min:4|confirmed',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'foto_sim' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ];

        if ($user && $user->isOwner()) {
            $rules['status_aktif'] = 'required|in:Aktif,Nonaktif,Sedang Jalan';
            $rules['password'] = 'nullable|string|min:4'; // Owner doesn't need confirmation input
        }

        $validated = $request->validate($rules);
        $plainPass = $request->input('password');
        unset($validated['password'], $validated['password_confirmation']);

        $uploadedDoc = false;

        if ($request->hasFile('foto_profil')) {
            if ($driver->foto_profil) {
                Storage::disk('public')->delete($driver->foto_profil);
            }
            $validated['foto_profil'] = $request->file('foto_profil')->store('drivers', 'public');
        }

        if ($request->hasFile('foto_ktp')) {
            if ($driver->foto_ktp) {
                Storage::disk('public')->delete($driver->foto_ktp);
            }
            $validated['foto_ktp'] = $request->file('foto_ktp')->store('drivers', 'public');
            $uploadedDoc = true;
        }

        if ($request->hasFile('foto_sim')) {
            if ($driver->foto_sim) {
                Storage::disk('public')->delete($driver->foto_sim);
            }
            $validated['foto_sim'] = $request->file('foto_sim')->store('drivers', 'public');
            $uploadedDoc = true;
        }

        if ($uploadedDoc && $user && $user->isSupir()) {
            $validated['status_verifikasi'] = 'Menunggu Verifikasi';
        }

        $driver->update($validated);

        // Update corresponding user account details & password
        $driverUser = User::where('driver_id', $driver->id)->first() ?? ($user && $user->isSupir() ? $user : null);
        if ($driverUser) {
            $userUpdate = [
                'name' => $validated['nama'],
                'no_hp' => $validated['nomor_hp'],
            ];
            if (!empty($plainPass)) {
                $userUpdate['password'] = Hash::make($plainPass);
                $userUpdate['password_hint'] = $plainPass;
            }
            $driverUser->update($userUpdate);
        }

        return redirect()->route('drivers.index')->with('success', 'Profil driver & password akun berhasil diperbarui.');
    }

    public function verify(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'status_verifikasi' => 'required|in:Terverifikasi,Ditolak',
            'catatan_verifikasi' => 'nullable|string|max:500',
        ]);

        $driver->update($validated);

        // Send Fonnte WhatsApp Notification directly to Driver's phone number
        try {
            if (!empty($driver->nomor_hp)) {
                if ($validated['status_verifikasi'] === 'Terverifikasi') {
                    $waMessage = "🎉 *SELAMAT! DOKUMEN DRIVER TERVERIFIKASI* 🎉\n"
                        . "------------------------------------------------\n"
                        . "Halo *" . $driver->nama . "*,\n"
                        . "Dokumen KTP & SIM Anda telah disetujui (Terverifikasi) oleh Owner TravelManager!\n\n"
                        . "STATUS: *Aktif & SIAP Menerima Tugas Travel* 🚘\n"
                        . "Terima kasih telah melengkapi berkas verifikasi pengemudi.";
                } else {
                    $catatan = !empty($validated['catatan_verifikasi']) ? $validated['catatan_verifikasi'] : 'Mohon unggah foto dokumen KTP/SIM yang lebih jelas.';
                    $waMessage = "⚠️ *PEMBERITAHUAN DOKUMEN DRIVER* ⚠️\n"
                        . "------------------------------------------------\n"
                        . "Halo *" . $driver->nama . "*,\n"
                        . "Dokumen KTP/SIM yang Anda unggah belum disetujui oleh Owner.\n\n"
                        . "📌 *Catatan Owner:* " . $catatan . "\n"
                        . "Mohon buka aplikasi TravelManager untuk mengunggah ulang foto dokumen KTP/SIM Anda.";
                }

                FonnteService::sendNotification($driver->nomor_hp, $waMessage);
            }
        } catch (\Throwable $e) {
            // Ignore WA error
        }

        $msg = $validated['status_verifikasi'] === 'Terverifikasi'
            ? "Dokumen KTP & SIM driver {$driver->nama} berhasil disetujui & notifikasi WA terkirim ke driver."
            : "Dokumen KTP & SIM driver {$driver->nama} ditolak & notifikasi WA terkirim ke driver.";

        return redirect()->route('drivers.index')->with('success', $msg);
    }

    public function destroy(Driver $driver)
    {
        if ($driver->foto_profil) {
            Storage::disk('public')->delete($driver->foto_profil);
        }
        if ($driver->foto_ktp) {
            Storage::disk('public')->delete($driver->foto_ktp);
        }
        if ($driver->foto_sim) {
            Storage::disk('public')->delete($driver->foto_sim);
        }

        User::where('driver_id', $driver->id)->delete();
        $driver->delete();

        return redirect()->route('drivers.index')->with('success', 'Data sopir berhasil dihapus.');
    }
}
