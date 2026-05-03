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
        Schema::create('sesi_tes', function (Blueprint $table) {
        $table->id();

        $table->foreignId('admin_id')
            ->constrained('users')
            ->cascadeOnDelete();

        $table->string('judul');
        $table->text('deskripsi')->nullable();

        $table->enum('tipe_tes', ['simulasi','mini','full']);

        $table->smallInteger('durasi_menit');

        $table->tinyInteger('jumlah_soal_reading')->default(0);
        $table->tinyInteger('jumlah_soal_listening')->default(0);
        $table->tinyInteger('jumlah_soal_structure')->default(0);

        $table->smallInteger('kuota_peserta')->default(30);
        $table->smallInteger('peserta_terdaftar')->default(0);

        $table->boolean('khusus_tes_full')->default(false);

        $table->string('password_sesi')->nullable();

        $table->dateTime('waktu_mulai');
        $table->dateTime('waktu_selesai');

        $table->boolean('tampilkan_hasil')->default(true);
        $table->boolean('tampilkan_pembahasan')->default(true);
        $table->boolean('is_published')->default(true);
        $table->boolean('is_aktif')->default(true);

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesi_tes');
    }
};
