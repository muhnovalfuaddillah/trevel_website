<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nomor_hp',
        'nomor_sim',
        'foto_profil',
        'foto_ktp',
        'foto_sim',
        'status_verifikasi',
        'catatan_verifikasi',
        'status_aktif',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
