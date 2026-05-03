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
        Schema::create('percobaan_tes', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->foreignId('sesi_id')
            ->constrained('sesi_tes')
            ->cascadeOnDelete();

        $table->dateTime('waktu_mulai');
        $table->dateTime('waktu_selesai')->nullable();
        $table->integer('last_question')->default(1);
        // skor per bagian
        $table->decimal('skor_listening', 5, 2)->default(0);
        $table->decimal('skor_structure', 5, 2)->default(0);
        $table->decimal('skor_reading', 5, 2)->default(0);
        $table->decimal('skor_total', 5, 2)->default(0);

        // statistik jawaban
        $table->smallInteger('jumlah_benar')->default(0);
        $table->smallInteger('jumlah_salah')->default(0);
        $table->smallInteger('jumlah_tidak_dijawab')->default(0);

        // status pengerjaan
        $table->enum('status', ['berlangsung','selesai','expired','dibatalkan'])
            ->default('berlangsung');

        // keamanan
        $table->tinyInteger('jumlah_pelanggaran')->default(0);
        $table->string('status_sanksi')->nullable();

        // autosave
        $table->timestamp('last_autosave_at')->nullable();

        // tracking
        $table->string('ip_address')->nullable();
        $table->string('browser_info')->nullable();

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('percobaan_tes');
    }
};
