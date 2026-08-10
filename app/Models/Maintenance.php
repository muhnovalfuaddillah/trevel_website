<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'tanggal_perawatan',
        'jenis_perawatan',
        'tujuan_perawatan',
        'biaya',
        'catatan',
    ];

    protected $casts = [
        'tanggal_perawatan' => 'date',
        'jenis_perawatan' => 'array',
        'biaya' => 'decimal:2',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
