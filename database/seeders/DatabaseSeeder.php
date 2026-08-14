<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Maintenance;
use App\Models\Expense;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today()->toDateString();
        $now = Carbon::now();

        // 1. Seed Vehicles with tarif_per_hari
        $v1 = Vehicle::create(['plat_nomor' => 'B 7890 TRV', 'merk' => 'Toyota HiAce Premio', 'kapasitas' => 12, 'tarif_per_hari' => 1750000.00, 'status' => 'Tersedia']);
        $v2 = Vehicle::create(['plat_nomor' => 'B 1234 NVA', 'merk' => 'Toyota Kijang Innova Zenix', 'kapasitas' => 7, 'tarif_per_hari' => 1250000.00, 'status' => 'Beroperasi']);
        $v3 = Vehicle::create(['plat_nomor' => 'D 5678 ELF', 'merk' => 'Isuzu Elf Long', 'kapasitas' => 19, 'tarif_per_hari' => 2000000.00, 'status' => 'Tersedia']);
        $v4 = Vehicle::create(['plat_nomor' => 'B 4321 SRV', 'merk' => 'Toyota HiAce Commuter', 'kapasitas' => 14, 'tarif_per_hari' => 1650000.00, 'status' => 'Servis']);
        $v5 = Vehicle::create(['plat_nomor' => 'D 9988 TRK', 'merk' => 'Mercedes-Benz Sprinter', 'kapasitas' => 16, 'tarif_per_hari' => 2800000.00, 'status' => 'Tersedia']);

        // 2. Seed Drivers (Test phone number 089629615301 for active driver)
        $d1 = Driver::create(['nama' => 'Bambang Sudrajat', 'nomor_hp' => '089629615301', 'nomor_sim' => 'SIM-A-982145', 'status_aktif' => 'Aktif']);
        $d2 = Driver::create(['nama' => 'Agus Hermawan', 'nomor_hp' => '081311223344', 'nomor_sim' => 'SIM-B1-762144', 'status_aktif' => 'Sedang Jalan']);
        $d3 = Driver::create(['nama' => 'Ahmad Fauzi', 'nomor_hp' => '085733445566', 'nomor_sim' => 'SIM-B1-882910', 'status_aktif' => 'Aktif']);
        $d4 = Driver::create(['nama' => 'Rudi Hartono', 'nomor_hp' => '081988776655', 'nomor_sim' => 'SIM-A-543210', 'status_aktif' => 'Nonaktif']);

        // 3. Seed Auth Users (Owner with Email Gmail & WhatsApp 089629615301)
        User::create([
            'name' => 'Owner Travel Management',
            'email' => 'owner@travel.com',
            'no_hp' => '089629615301',
            'password' => Hash::make('password'),
            'password_hint' => 'password',
            'role' => 'owner',
            'driver_id' => null,
        ]);

        User::create([
            'name' => 'Bambang Sudrajat',
            'email' => 'bambang@travel.com',
            'no_hp' => '089629615301',
            'password' => Hash::make('password'),
            'password_hint' => 'password',
            'role' => 'supir',
            'driver_id' => $d1->id,
        ]);

        User::create([
            'name' => 'Agus Hermawan',
            'email' => 'agus@travel.com',
            'no_hp' => '081311223344',
            'password' => Hash::make('password'),
            'password_hint' => 'password',
            'role' => 'supir',
            'driver_id' => $d2->id,
        ]);

        // 4. Seed Schedules FIRST
        $s1 = Schedule::create([
            'tanggal_keberangkatan' => $now->format('Y-m-d H:i:s'),
            'vehicle_id' => $v2->id,
            'driver_id' => $d2->id,
            'rute' => 'Jakarta (Pulo Gebang) - Bandung (Dipatiukur) (2 Hari)',
            'status_perjalanan' => 'Dalam Perjalanan'
        ]);

        $s2 = Schedule::create([
            'tanggal_keberangkatan' => Carbon::tomorrow()->setTime(8, 0)->format('Y-m-d H:i:s'),
            'vehicle_id' => $v1->id,
            'driver_id' => $d1->id,
            'rute' => 'Jakarta (Cilandak) - Semarang (Simpang Lima) (3 Hari)',
            'status_perjalanan' => 'Terjadwal'
        ]);

        // 5. Seed Bookings LINKED to Vehicles, Drivers, and Schedules
        Booking::create([
            'schedule_id' => $s1->id,
            'vehicle_id' => $v2->id,
            'driver_id' => $d2->id,
            'harga_dp' => 2500000.00,
            'asal' => 'Jakarta (Pulo Gebang)',
            'tujuan' => 'Bandung (Dipatiukur)',
            'tanggal_berangkat' => $today,
            'lama_hari' => 2,
            'tanggal_selesai' => Carbon::parse($today)->addDays(1)->toDateString(),
            'jumlah_kursi' => 7,
            'tarif' => 2500000.00,
            'status_pembayaran' => 'Lunas',
            'status_verifikasi' => 'Terverifikasi',
        ]);

        Booking::create([
            'schedule_id' => $s2->id,
            'vehicle_id' => $v1->id,
            'driver_id' => $d1->id,
            'harga_dp' => 1500000.00,
            'asal' => 'Jakarta (Cilandak)',
            'tujuan' => 'Semarang (Simpang Lima)',
            'tanggal_berangkat' => $today,
            'lama_hari' => 3,
            'tanggal_selesai' => Carbon::parse($today)->addDays(2)->toDateString(),
            'jumlah_kursi' => 12,
            'tarif' => 5250000.00,
            'status_pembayaran' => 'DP',
            'status_verifikasi' => 'Terverifikasi',
        ]);

        // 6. Seed Maintenances
        Maintenance::create([
            'vehicle_id' => $v4->id,
            'tanggal_perawatan' => $today,
            'jenis_perawatan' => ['Servis Mesin', 'Ganti Oli'],
            'tujuan_perawatan' => 'Perbaikan',
            'biaya' => 1250000.00,
            'catatan' => 'Perbaikan radiator dan ganti oli mesin 10W-40'
        ]);

        // 7. Seed Expenses
        Expense::create([
            'schedule_id' => $s1->id,
            'tanggal' => $today,
            'kategori' => 'BBM',
            'jumlah' => 350000.00,
            'keterangan' => 'BBM Pertamina Dex Jakarta - Bandung'
        ]);

        Expense::create([
            'schedule_id' => $s1->id,
            'tanggal' => $today,
            'kategori' => 'Tol',
            'jumlah' => 125000.00,
            'keterangan' => 'E-Toll GT Cikarang Utama & Pasteur'
        ]);
    }
}
