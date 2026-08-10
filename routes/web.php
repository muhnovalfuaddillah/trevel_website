<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;

// Auth Routes (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard (Customized per role)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Profile Route (Available to both)
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/owner', [ProfileController::class, 'updateOwner'])->name('profile.updateOwner');

    // Shared Operations (Booking Travel & Jadwal Perjalanan & Driver Profile)
    Route::resource('bookings', BookingController::class)->except(['create', 'edit', 'show']);
    Route::resource('schedules', ScheduleController::class)->except(['create', 'edit', 'show']);
    Route::get('/drivers', [DriverController::class, 'index'])->name('drivers.index');
    Route::put('/drivers/{driver}', [DriverController::class, 'update'])->name('drivers.update');

    // Owner-Only Restricted Management Modules
    Route::middleware('role:owner')->group(function () {
        Route::resource('vehicles', VehicleController::class)->except(['create', 'edit', 'show']);
        Route::post('/drivers', [DriverController::class, 'store'])->name('drivers.store');
        Route::delete('/drivers/{driver}', [DriverController::class, 'destroy'])->name('drivers.destroy');
        Route::post('/drivers/{driver}/verify', [DriverController::class, 'verify'])->name('drivers.verify');
        Route::post('/bookings/{booking}/verify', [BookingController::class, 'verify'])->name('bookings.verify');
        Route::resource('maintenances', MaintenanceController::class)->except(['create', 'edit', 'show']);
        Route::resource('expenses', ExpenseController::class)->except(['create', 'edit', 'show']);
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });
});
