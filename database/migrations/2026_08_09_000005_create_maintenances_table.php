<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->date('tanggal_perawatan');
            $table->json('jenis_perawatan'); // ['Ganti oli', 'Servis mesin', 'Ganti ban', 'Servis AC', 'Lainnya']
            $table->enum('tujuan_perawatan', ['Rutin', 'Perbaikan'])->default('Rutin');
            $table->decimal('biaya', 12, 2);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
