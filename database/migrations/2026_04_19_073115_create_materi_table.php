<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materi', function (Blueprint $table) {
        $table->id();

        $table->foreignId('created_by')
            ->constrained('users')
            ->cascadeOnDelete();

        $table->enum('kategori', ['reading','listening','structure']);

        $table->string('judul');
        $table->text('deskripsi')->nullable();

        $table->longText('konten')->nullable(); // untuk reading & structure

        $table->string('file_url')->nullable(); // audio/pdf/video
        $table->enum('tipe_file', ['none','pdf','audio','video'])->default('none');

        $table->smallInteger('estimasi_menit')->default(5);
        $table->smallInteger('urutan')->default(0);

        $table->boolean('is_aktif')->default(true);

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materi');
    }
};
