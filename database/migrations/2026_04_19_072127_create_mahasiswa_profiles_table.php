<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswa_profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->unique()
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('nim_nip')->nullable();
            $table->string('status_polman')->nullable();
            $table->string('program_studi')->nullable();
            $table->year('angkatan')->nullable();
            $table->string('no_telepon', 20)->nullable();
            $table->string('foto_profil_url')->nullable();

            $table->enum('level_latihan', ['easy','medium','hard'])->default('easy');

            $table->tinyInteger('jumlah_absen')->default(0);
            $table->boolean('status_blacklist')->default(false);
            $table->date('blacklist_until')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa_profiles');
    }
};
