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

        
        User::create([
            'name' => 'Owner Travel Management',
            'email' => 'owner@travel.com',
            'no_hp' => '089629615301',
            'password' => Hash::make('password'),
            'password_hint' => 'password',
            'role' => 'owner',
            'driver_id' => null,
        ]);
    }
}
