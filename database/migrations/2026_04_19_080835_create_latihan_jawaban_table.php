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
        Schema::create('latihan_jawaban', function (Blueprint $table) {
        $table->id();

        $table->foreignId('latihan_id')
            ->constrained('latihan')
            ->cascadeOnDelete();

        $table->foreignId('soal_id')
            ->constrained('bank_soal')
            ->cascadeOnDelete();

        $table->tinyInteger('urutan_soal');

        $table->enum('jawaban_dipilih', ['a','b','c','d'])->nullable();

        $table->boolean('is_benar')->nullable();
        $table->index(['latihan_id', 'urutan_soal']);
        $table->enum('status_soal', ['belum','dijawab','ragu'])
            ->default('belum');

        $table->boolean('is_synced')->default(true);

        $table->timestamp('waktu_dijawab')->nullable();

        $table->timestamps();

        // penting!
        $table->unique(['latihan_id', 'soal_id']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('latihan_jawaban');
    }
};
