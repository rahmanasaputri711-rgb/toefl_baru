<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * FIX: Buat sesi_id nullable di percobaan_tes
 * agar simulasi & mini test bisa berjalan tanpa sesi admin.
 * Sebelumnya migration ini kosong — itulah kenapa simulasi selalu error
 * "Column 'sesi_id' cannot be null".
 */
return new class extends Migration {
    public function up(): void {
        Schema::table('percobaan_tes', function (Blueprint $table) {
            // Drop FK dulu sebelum ubah kolom
            $table->dropForeign(['sesi_id']);
            // Jadikan nullable — simulasi & mini tidak punya sesi admin
            $table->foreignId('sesi_id')->nullable()->change();
            // Re-add FK dengan nullable
            $table->foreign('sesi_id')
                  ->references('id')->on('sesi_tes')
                  ->nullOnDelete();
        });
    }

    public function down(): void {
        Schema::table('percobaan_tes', function (Blueprint $table) {
            $table->dropForeign(['sesi_id']);
            $table->foreignId('sesi_id')->nullable(false)->change();
            $table->foreign('sesi_id')
                  ->references('id')->on('sesi_tes')
                  ->cascadeOnDelete();
        });
    }
};
