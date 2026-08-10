<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal_keberangkatan',
        'vehicle_id',
        'driver_id',
        'rute',
        'status_perjalanan',
    ];

    protected $casts = [
        'tanggal_keberangkatan' => 'datetime',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
