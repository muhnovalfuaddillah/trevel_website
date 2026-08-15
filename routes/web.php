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

// Root Route (Redirect to Login or Dashboard)
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');

// Auth Routes (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard (Customized per role)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Route (Available to both)
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/owner', [ProfileController::class, 'updateOwner'])->name('profile.updateOwner');

    // Redirect bookings directly to Jadwal Perjalanan (/schedules)
    Route::get('/bookings', function () {
        return redirect()->route('schedules.index');
    })->name('bookings.index');

    // Shared Operations (Jadwal Perjalanan, Maintenance, Expenses & Driver Profile & Vehicles Read)
    Route::resource('schedules', ScheduleController::class)->except(['create', 'edit', 'show']);
    Route::resource('maintenances', MaintenanceController::class)->except(['create', 'edit', 'show']);
    Route::resource('expenses', ExpenseController::class)->except(['create', 'edit', 'show']);
    Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
    Route::get('/drivers', [DriverController::class, 'index'])->name('drivers.index');
    Route::put('/drivers/{driver}', [DriverController::class, 'update'])->name('drivers.update');

    // Owner-Only Restricted Management Modules
    Route::middleware('role:owner')->group(function () {
        Route::post('/vehicles', [VehicleController::class, 'store'])->name('vehicles.store');
        Route::put('/vehicles/{vehicle}', [VehicleController::class, 'update'])->name('vehicles.update');
        Route::delete('/vehicles/{vehicle}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');
        Route::post('/drivers', [DriverController::class, 'store'])->name('drivers.store');
        Route::delete('/drivers/{driver}', [DriverController::class, 'destroy'])->name('drivers.destroy');
        Route::post('/drivers/{driver}/verify', [DriverController::class, 'verify'])->name('drivers.verify');
        Route::post('/bookings/{booking}/verify', [BookingController::class, 'verify'])->name('bookings.verify');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/expenses', [ReportController::class, 'expensesReport'])->name('reports.expenses');
    });
});
