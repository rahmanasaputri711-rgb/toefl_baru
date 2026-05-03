<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('riwayat_absen', function (Blueprint $table) {
        $table->id();
        $table->index(['user_id']);
        $table->foreignId('user_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->foreignId('sesi_id')
            ->constrained('sesi_tes')
            ->cascadeOnDelete();

        $table->foreignId('pendaftaran_id')
            ->constrained('pendaftaran_tes')
            ->cascadeOnDelete();

        $table->tinyInteger('absen_ke');

        $table->text('alasan_user')->nullable();
        $table->text('keterangan_admin')->nullable();

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_absen');
    }
};
