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
        'kilometer',
        'petugas_perawatan',
        'catatan',
        'foto_bukti',
    ];

    protected $casts = [
        'tanggal_perawatan' => 'date',
        'jenis_perawatan' => 'array',
        'foto_bukti' => 'array',
        'biaya' => 'decimal:2',
        'kilometer' => 'integer',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
