<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->onDelete('cascade');
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->onDelete('cascade');
            $table->foreignId('schedule_id')->nullable()->constrained('schedules')->onDelete('set null');
            $table->decimal('harga_dp', 12, 2)->default(0);
            $table->string('asal');
            $table->string('tujuan');
            $table->date('tanggal_berangkat');
            $table->integer('lama_hari')->default(1);
            $table->date('tanggal_selesai')->nullable();
            $table->integer('jumlah_kursi');
            $table->decimal('tarif', 12, 2);
            $table->enum('status_pembayaran', ['Lunas', 'DP'])->default('DP');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
