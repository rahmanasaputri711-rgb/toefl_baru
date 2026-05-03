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
        Schema::create('grafik_progress', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->enum('sumber', [
            'kuis_materi',
            'latihan',
            'tes_simulasi',
            'tes_mini',
            'tes_full'
        ]);

        $table->unsignedBigInteger('sumber_id')->nullable();

        $table->date('tanggal');
        $table->index(['user_id', 'tanggal']);
        $table->decimal('skor_listening', 5, 2)->default(0);
        $table->decimal('skor_structure', 5, 2)->default(0);
        $table->decimal('skor_reading', 5, 2)->default(0);

        $table->decimal('skor_toefl_estimasi', 5, 2)->default(0);

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grafik_progress');
    }
};
