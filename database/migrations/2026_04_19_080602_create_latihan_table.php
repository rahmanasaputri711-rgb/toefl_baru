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
        Schema::create('latihan', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->enum('kategori', ['reading','listening','structure']);

        $table->enum('level', ['easy','medium','hard']);

        $table->tinyInteger('jumlah_soal');

        $table->dateTime('waktu_mulai');
        $table->dateTime('waktu_selesai')->nullable();

        $table->decimal('skor', 5, 2)->default(0);
        $table->integer('last_question')->default(1);
        $table->tinyInteger('jumlah_benar')->default(0);

        $table->enum('status', ['berlangsung','selesai'])
            ->default('berlangsung');

        $table->timestamp('last_autosave_at')->nullable();

        $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('latihan');
    }
};
