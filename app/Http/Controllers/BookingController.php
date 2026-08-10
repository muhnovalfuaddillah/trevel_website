<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Driver;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user && $user->isSupir()) {
            $driverId = $user->driver_id;
            if (!$driverId) {
                $matched = Driver::where('nama', 'LIKE', '%' . $user->name . '%')->first();
                $driverId = $matched->id ?? 0;
            }
            // Supir strictly sees ONLY their own assigned bookings
            $bookings = Booking::where('driver_id', $driverId)->with(['vehicle', 'driver', 'schedule'])->latest()->take(10)->get();
        } else {
            // Owner sees all bookings
            $bookings = Booking::with(['vehicle', 'driver', 'schedule'])->latest()->take(10)->get();
        }

        // Active drivers and ready vehicles (excluding vehicles already scheduled 'Terjadwal' or 'Dalam Perjalanan')
        $scheduledVehicleIds = Schedule::whereIn('status_perjalanan', ['Terjadwal', 'Dalam Perjalanan'])->pluck('vehicle_id')->toArray();
        $readyVehicles = Vehicle::where('status', 'Tersedia')
            ->whereNotIn('id', $scheduledVehicleIds)
            ->get();

        $allVehicles = Vehicle::all();
        $readyDrivers = Driver::where('status_aktif', 'Aktif')->get();
        $allDrivers = Driver::all();
        $schedules = Schedule::with(['driver', 'vehicle'])->whereIn('status_perjalanan', ['Terjadwal', 'Dalam Perjalanan'])->get();

        return view('bookings.index', compact('bookings', 'readyVehicles', 'allVehicles', 'readyDrivers', 'allDrivers', 'schedules', 'scheduledVehicleIds'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:drivers,id',
            'harga_dp' => 'required|numeric|min:0',
            'asal' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'tanggal_berangkat' => 'required|date',
            'lama_hari' => 'required|integer|min:1',
            'jumlah_kursi' => 'required|integer|min:1',
            'tarif' => 'required|numeric|min:0',
            'status_pembayaran' => 'required|in:Lunas,DP',
        ]);

        // Calculate return date (tanggal_selesai) based on departure date and total days
        $startDate = Carbon::parse($validated['tanggal_berangkat']);
        $validated['tanggal_selesai'] = $startDate->copy()->addDays($validated['lama_hari'] - 1)->toDateString();

        // Validate vehicle capacity
        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
        if ($validated['jumlah_kursi'] > $vehicle->kapasitas) {
            return back()->withErrors([
                'jumlah_kursi' => "Jumlah kursi dipesan ({$validated['jumlah_kursi']} kursi) melebihi kapasitas armada {$vehicle->plat_nomor} ({$vehicle->merk}) yang hanya berkapasitas {$vehicle->kapasitas} kursi."
            ])->withInput();
        }

        // If status is Lunas, DP equals full tarif
        if ($validated['status_pembayaran'] === 'Lunas') {
            $validated['harga_dp'] = $validated['tarif'];
        }

        $rute = $validated['asal'] . ' - ' . $validated['tujuan'] . " ({$validated['lama_hari']} Hari)";

        // Owner bookings are auto-verified & auto-create schedules. Supir bookings require Owner verification!
        if ($user && $user->isOwner()) {
            $validated['status_verifikasi'] = 'Terverifikasi';
            $schedule = Schedule::firstOrCreate(
                [
                    'vehicle_id' => $validated['vehicle_id'],
                    'driver_id' => $validated['driver_id'],
                    'rute' => $rute,
                    'tanggal_keberangkatan' => $validated['tanggal_berangkat'] . ' 08:00:00',
                ],
                [
                    'status_perjalanan' => 'Terjadwal',
                ]
            );
            $validated['schedule_id'] = $schedule->id;
            $msg = "Booking travel berhasil disimpan & Jadwal Perjalanan otomatis terbit.";
        } else {
            $validated['status_verifikasi'] = 'Menunggu Verifikasi';
            $validated['schedule_id'] = null;
            $msg = "Booking travel berhasil diajukan. Status: Menunggu Verifikasi Owner sebelum jadwal terbit.";
        }

        $booking = Booking::create($validated);

        // Send Fonnte WhatsApp API Notification to Owner
        try {
            $driver = Driver::find($validated['driver_id']);
            $ownerUser = User::where('role', 'owner')->first();
            $ownerPhone = $ownerUser->no_hp ?? '089629615301';

            $totalFormatted = 'Rp ' . number_format($validated['tarif'], 0, ',', '.');
            $dpFormatted = 'Rp ' . number_format($validated['harga_dp'], 0, ',', '.');

            $waOwnerMessage = "🚨 *NOTIFIKASI BOOKING TRAVEL BARU!* 🚨\n"
                . "------------------------------------------------\n"
                . "Halo Owner TravelManager, ada booking travel baru:\n\n"
                . "🚘 *Armada Mobil:* " . ($vehicle->plat_nomor ?? '-') . " (" . ($vehicle->merk ?? '') . ")\n"
                . "👤 *Driver:* " . ($driver->nama ?? 'Supir') . " (HP: " . ($driver->nomor_hp ?? '-') . ")\n"
                . "📍 *Rute:* " . $validated['asal'] . " ➔ " . $validated['tujuan'] . "\n"
                . "📅 *Tgl Berangkat:* " . date('d/m/Y', strtotime($validated['tanggal_berangkat'])) . " (" . $validated['lama_hari'] . " Hari)\n"
                . "💺 *Kursi:* " . $validated['jumlah_kursi'] . " Seat\n"
                . "💰 *Total Tarif:* " . $totalFormatted . "\n"
                . "💳 *Status Pembayaran:* " . $validated['status_pembayaran'] . " (DP: " . $dpFormatted . ")\n"
                . "------------------------------------------------\n"
                . "📌 *Status Verifikasi:* " . $validated['status_verifikasi'] . "\n"
                . "Silakan buka sistem TravelManager untuk verifikasi & terbitkan jadwal.";

            FonnteService::sendNotification($ownerPhone, $waOwnerMessage);
        } catch (\Throwable $e) {
            Log::error("Error sending WA on booking store: " . $e->getMessage());
        }

        return redirect()->route('bookings.index')->with('success', $msg);
    }

    public function update(Request $request, Booking $booking)
    {
        $user = auth()->user();
        if ($user && $user->isSupir()) {
            $scheduleStatus = $booking->schedule->status_perjalanan ?? 'Terjadwal';
            $isVerified = ($booking->status_verifikasi ?? 'Terverifikasi') === 'Terverifikasi';

            if ($isVerified || in_array($scheduleStatus, ['Dalam Perjalanan', 'Selesai'])) {
                return redirect()->route('bookings.index')->with('error', 'Akses Ditolak! Booking travel yang telah disetujui Owner atau rutenya sedang dalam perjalanan/selesai tidak dapat diubah oleh Supir.');
            }
        }

        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:drivers,id',
            'harga_dp' => 'required|numeric|min:0',
            'asal' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'tanggal_berangkat' => 'required|date',
            'lama_hari' => 'required|integer|min:1',
            'jumlah_kursi' => 'required|integer|min:1',
            'tarif' => 'required|numeric|min:0',
            'status_pembayaran' => 'required|in:Lunas,DP',
        ]);

        $startDate = Carbon::parse($validated['tanggal_berangkat']);
        $validated['tanggal_selesai'] = $startDate->copy()->addDays($validated['lama_hari'] - 1)->toDateString();

        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
        if ($validated['jumlah_kursi'] > $vehicle->kapasitas) {
            return back()->withErrors([
                'jumlah_kursi' => "Jumlah kursi dipesan ({$validated['jumlah_kursi']} kursi) melebihi kapasitas armada {$vehicle->plat_nomor} ({$vehicle->merk}) yang hanya berkapasitas {$vehicle->kapasitas} kursi."
            ])->withInput();
        }

        if ($validated['status_pembayaran'] === 'Lunas') {
            $validated['harga_dp'] = $validated['tarif'];
        }

        $rute = $validated['asal'] . ' - ' . $validated['tujuan'] . " ({$validated['lama_hari']} Hari)";

        // If booking is verified, update matching schedule
        if ($booking->status_verifikasi === 'Terverifikasi') {
            $schedule = Schedule::firstOrCreate(
                [
                    'vehicle_id' => $validated['vehicle_id'],
                    'driver_id' => $validated['driver_id'],
                    'rute' => $rute,
                    'tanggal_keberangkatan' => $validated['tanggal_berangkat'] . ' 08:00:00',
                ],
                [
                    'status_perjalanan' => 'Terjadwal',
                ]
            );
            $validated['schedule_id'] = $schedule->id;
        }

        $booking->update($validated);

        return redirect()->route('bookings.index')->with('success', 'Data booking travel berhasil diperbarui.');
    }

    public function verify(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status_verifikasi' => 'required|in:Terverifikasi,Ditolak',
            'catatan_verifikasi' => 'nullable|string|max:500',
        ]);

        if ($validated['status_verifikasi'] === 'Terverifikasi') {
            $rute = $booking->asal . ' - ' . $booking->tujuan . " ({$booking->lama_hari} Hari)";
            $schedule = Schedule::firstOrCreate(
                [
                    'vehicle_id' => $booking->vehicle_id,
                    'driver_id' => $booking->driver_id,
                    'rute' => $rute,
                    'tanggal_keberangkatan' => $booking->tanggal_berangkat->format('Y-m-d') . ' 08:00:00',
                ],
                [
                    'status_perjalanan' => 'Terjadwal',
                ]
            );
            $validated['schedule_id'] = $schedule->id;
            $msg = "Booking travel diverifikasi oleh Owner & Jadwal Perjalanan resmi otomatis terbit!";
        } else {
            $msg = "Booking travel ditolak oleh Owner.";
        }

        $booking->update($validated);

        // Ensure driver & vehicle relations are loaded
        $booking->load(['driver', 'vehicle']);
        $driver = $booking->driver;
        $vehicle = $booking->vehicle;

        // Send Fonnte WhatsApp Notification directly to Driver's Phone Number
        try {
            $targetPhone = !empty($driver->nomor_hp) ? $driver->nomor_hp : '089629615301';

            if ($validated['status_verifikasi'] === 'Terverifikasi') {
                $waDriverMsg = "📢 *PEMBERITAHUAN VERIFIKASI BOOKING & JADWAL* 📢\n"
                    . "------------------------------------------------\n"
                    . "Halo *" . ($driver->nama ?? 'Driver') . "*,\n"
                    . "Booking travel rute Anda telah *DIVERIFIKASI & DISETUJUI* oleh Owner:\n\n"
                    . "🚘 *Armada Mobil:* " . ($vehicle->plat_nomor ?? '-') . " (" . ($vehicle->merk ?? '') . ")\n"
                    . "📍 *Rute Perjalanan:* " . $booking->asal . " ➔ " . $booking->tujuan . "\n"
                    . "📅 *Tgl Keberangkatan:* " . $booking->tanggal_berangkat->format('d/m/Y') . " (" . $booking->lama_hari . " Hari)\n"
                    . "💺 *Jumlah Kursi:* " . $booking->jumlah_kursi . " Seat\n"
                    . "------------------------------------------------\n"
                    . "📌 *Status:* Jadwal Resmi Terbit (Terjadwal)\n"
                    . "Silakan siapkan armada kendaraan & cek aplikasi TravelManager Anda.";
            } else {
                $catatan = !empty($validated['catatan_verifikasi']) ? $validated['catatan_verifikasi'] : 'Mohon periksa data rute/mobil.';
                $waDriverMsg = "⚠️ *PEMBERITAHUAN BOOKING DITOLAK* ⚠️\n"
                    . "------------------------------------------------\n"
                    . "Halo *" . ($driver->nama ?? 'Driver') . "*,\n"
                    . "Pengajuan booking travel rute " . $booking->asal . " ➔ " . $booking->tujuan . " ditolak oleh Owner.\n\n"
                    . "📌 *Catatan Owner:* " . $catatan;
            }

            FonnteService::sendNotification($targetPhone, $waDriverMsg);
        } catch (\Throwable $e) {
            Log::error("Error sending WA on booking verify: " . $e->getMessage());
        }

        return redirect()->route('bookings.index')->with('success', $msg);
    }

    public function destroy(Booking $booking)
    {
        $user = auth()->user();
        if ($user && $user->isSupir()) {
            $scheduleStatus = $booking->schedule->status_perjalanan ?? 'Terjadwal';
            $isVerified = ($booking->status_verifikasi ?? 'Terverifikasi') === 'Terverifikasi';

            if ($isVerified || in_array($scheduleStatus, ['Dalam Perjalanan', 'Selesai'])) {
                return redirect()->route('bookings.index')->with('error', 'Akses Ditolak! Booking travel yang telah disetujui Owner atau rutenya sedang dalam perjalanan/selesai tidak dapat dihapus oleh Supir.');
            }
        }

        $booking->delete();
        return redirect()->route('bookings.index')->with('success', 'Booking berhasil dihapus.');
    }
}
