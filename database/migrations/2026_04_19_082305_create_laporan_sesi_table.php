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
        Schema::create('laporan_sesi', function (Blueprint $table) {
        $table->id();

        $table->foreignId('sesi_id')
            ->unique()
            ->constrained('sesi_tes')
            ->cascadeOnDelete();

        $table->smallInteger('total_peserta_terdaftar')->default(0);
        $table->smallInteger('total_peserta_hadir')->default(0);
        $table->smallInteger('total_peserta_tidak_hadir')->default(0);

        $table->decimal('rata_skor_listening', 5, 2)->default(0);
        $table->decimal('rata_skor_structure', 5, 2)->default(0);
        $table->decimal('rata_skor_reading', 5, 2)->default(0);
        $table->decimal('rata_skor_total', 5, 2)->default(0);

        $table->decimal('skor_tertinggi', 5, 2)->default(0);
        $table->decimal('skor_terendah', 5, 2)->default(0);

        $table->smallInteger('total_pelanggaran')->default(0);
        $table->timestamp('generated_at')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_sesi');
    }
};
