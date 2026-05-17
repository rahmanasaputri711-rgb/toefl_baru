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
      Schema::create('listening_audio_paket', function (Blueprint $table) {

    $table->id();

    $table->string('nama', 200);

    $table->string('tipe_paket', 50);

    $table->string('audio_url');

    $table->integer('durasi_detik')->default(0);

    $table->integer('jumlah_soal')->default(0);

    $table->boolean('is_aktif')->default(true);

    $table->unsignedBigInteger('created_by')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listening_audio_paket');
    }
};
