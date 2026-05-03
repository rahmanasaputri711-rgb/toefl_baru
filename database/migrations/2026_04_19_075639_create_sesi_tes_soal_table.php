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
        Schema::create('sesi_tes_soal', function (Blueprint $table) {
        $table->id();

        $table->foreignId('sesi_id')
            ->constrained('sesi_tes')
            ->cascadeOnDelete();
        $table->index(['sesi_id', 'urutan_acak']);
        $table->foreignId('soal_id')
            ->constrained('bank_soal')
            ->cascadeOnDelete();

        $table->smallInteger('urutan_acak'); // hasil Fisher-Yates

        $table->enum('bagian', ['listening','structure','reading']);

        $table->timestamps();

        // supaya soal tidak dobel dalam 1 sesi
        $table->unique(['sesi_id', 'soal_id']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesi_tes_soal');
    }
};
