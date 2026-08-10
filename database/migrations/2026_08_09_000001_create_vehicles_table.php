<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plat_nomor')->unique();
            $table->string('merk');
            $table->integer('kapasitas');
            $table->decimal('tarif_per_hari', 12, 2)->default(1500000);
            $table->enum('status', ['Tersedia', 'Beroperasi', 'Servis'])->default('Tersedia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
