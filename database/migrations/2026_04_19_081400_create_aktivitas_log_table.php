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
        Schema::create('aktivitas_log', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->foreignId('percobaan_id')
            ->nullable()
            ->constrained('percobaan_tes')
            ->cascadeOnDelete();

        $table->string('tipe_aksi'); 
        // contoh: login, logout, tab_switch, screenshot, fullscreen_exit

        $table->text('detail')->nullable();
        $table->index(['percobaan_id']);
        $table->tinyInteger('pelanggaran_ke')->nullable();

        $table->string('ip_address')->nullable();

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivitas_log');
    }
};
