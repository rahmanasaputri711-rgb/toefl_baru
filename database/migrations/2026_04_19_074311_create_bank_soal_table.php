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
        Schema::create('bank_soal', function (Blueprint $table) {
        $table->id();

        $table->foreignId('created_by')
            ->constrained('users')
            ->cascadeOnDelete();

        $table->enum('kategori', ['reading','listening','structure']);

        $table->enum('tingkat_kesulitan', ['easy','medium','hard'])
            ->default('medium');

        $table->boolean('untuk_tes_full')->default(true);

        $table->text('pertanyaan');
        $table->string('group_id')->nullable();
        $table->longText('passage_teks')->nullable(); // reading
        $table->string('audio_url')->nullable(); // listening
        $table->smallInteger('durasi_audio_detik')->nullable();

        $table->text('pilihan_a');
        $table->text('pilihan_b');
        $table->text('pilihan_c');
        $table->text('pilihan_d');

        $table->enum('jawaban_benar', ['a','b','c','d']);

        $table->text('pembahasan')->nullable();

        $table->tinyInteger('bobot_nilai')->default(1);

        $table->boolean('is_aktif')->default(true);

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_soal');
    }
};
