<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('grup_soal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->enum('kategori', ['listening','reading']);
            $table->string('part', 20)->nullable();          // A/B/C
            $table->string('judul')->nullable();              // label opsional
            $table->longText('passage_teks')->nullable();     // untuk reading
            $table->string('audio_url')->nullable();          // untuk listening
            $table->smallInteger('durasi_audio_detik')->nullable();
            $table->text('deskripsi')->nullable();
            $table->unsignedSmallInteger('jumlah_soal')->default(0); // cache count
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('grup_soal');
    }
};
