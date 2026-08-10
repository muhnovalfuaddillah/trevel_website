<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->string('foto_profil')->nullable()->after('nomor_sim');
            $table->string('foto_ktp')->nullable()->after('foto_profil');
            $table->string('foto_sim')->nullable()->after('foto_ktp');
            $table->enum('status_verifikasi', ['Belum Upload', 'Menunggu Verifikasi', 'Terverifikasi', 'Ditolak'])->default('Belum Upload')->after('foto_sim');
            $table->text('catatan_verifikasi')->nullable()->after('status_verifikasi');
        });
    }

    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn(['foto_profil', 'foto_ktp', 'foto_sim', 'status_verifikasi', 'catatan_verifikasi']);
        });
    }
};
