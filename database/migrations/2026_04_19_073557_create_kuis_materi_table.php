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
        Schema::create('kuis_materi', function (Blueprint $table) {
        $table->id();

        $table->foreignId('materi_id')
            ->constrained('materi')
            ->cascadeOnDelete();

        $table->text('pertanyaan');

        $table->text('pilihan_a');
        $table->text('pilihan_b');
        $table->text('pilihan_c');
        $table->text('pilihan_d');

        $table->enum('jawaban_benar', ['a','b','c','d']);

        $table->text('penjelasan')->nullable();

        $table->tinyInteger('urutan')->default(1);

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kuis_materi');
    }
};
