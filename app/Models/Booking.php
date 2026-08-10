<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'driver_id',
        'schedule_id',
        'harga_dp',
        'asal',
        'tujuan',
        'tanggal_berangkat',
        'lama_hari',
        'tanggal_selesai',
        'jumlah_kursi',
        'tarif',
        'status_pembayaran',
        'status_verifikasi',
        'catatan_verifikasi',
    ];

    protected $casts = [
        'tanggal_berangkat' => 'date',
        'tanggal_selesai' => 'date',
        'harga_dp' => 'decimal:2',
        'tarif' => 'decimal:2',
    ];

    protected $appends = ['sisa_pelunasan'];

    public function getSisaPelunasanAttribute()
    {
        if ($this->status_pembayaran === 'Lunas') {
            return 0;
        }
        return max(0, $this->tarif - $this->harga_dp);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
