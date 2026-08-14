<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('maintenances', function (Blueprint $table) {
            $table->string('petugas_perawatan')->nullable()->after('biaya');
            $table->json('foto_bukti')->nullable()->after('catatan');
        });
    }

    public function down(): void
    {
        Schema::table('maintenances', function (Blueprint $table) {
            $table->dropColumn(['petugas_perawatan', 'foto_bukti']);
        });
    }
};
