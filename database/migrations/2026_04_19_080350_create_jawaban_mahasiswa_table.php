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
            Schema::create('jawaban_mahasiswa', function (Blueprint $table) {
        $table->id();

        $table->foreignId('percobaan_id')
            ->constrained('percobaan_tes')
            ->cascadeOnDelete();

        $table->foreignId('soal_id')
            ->constrained('bank_soal')
            ->cascadeOnDelete();

        $table->enum('jawaban_dipilih', ['a','b','c','d'])->nullable();

        $table->boolean('is_benar')->nullable(); // dihitung saat submit

        $table->enum('status_soal', ['belum','dijawab','ragu'])
            ->default('belum');

        $table->boolean('is_synced')->default(true);
        $table->integer('nomor_soal');
        $table->timestamp('waktu_dijawab')->nullable();

        $table->smallInteger('durasi_detik')->nullable();

        $table->timestamps();

        // supaya 1 soal hanya 1 jawaban per percobaan
        $table->unique(['percobaan_id', 'soal_id']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_mahasiswa');
    }
};
