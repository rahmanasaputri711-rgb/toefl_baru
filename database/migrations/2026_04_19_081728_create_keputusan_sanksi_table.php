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
        Schema::create('keputusan_sanksi', function (Blueprint $table) {
        $table->id();

        $table->foreignId('percobaan_id')
            ->constrained('percobaan_tes')
            ->cascadeOnDelete();

        $table->foreignId('user_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->foreignId('admin_id')
            ->constrained('users')
            ->cascadeOnDelete();

        $table->enum('jenis_sanksi', [
            'peringatan_tertulis',
            'blokir_sementara',
            'blokir_permanen',
            'lainnya'
        ]);

        $table->smallInteger('durasi_blokir_hari')->nullable();

        $table->text('catatan');

        $table->boolean('sudah_dieksekusi')->default(false);
        $table->timestamp('dieksekusi_at')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keputusan_sanksi');
    }
};
