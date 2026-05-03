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
        Schema::create('pendaftaran_tes', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->foreignId('sesi_id')
            ->constrained('sesi_tes')
            ->cascadeOnDelete();

        $table->string('nim_nip');
        $table->enum('status_polman', ['mahasiswa','dosen','staf','alumni']);
        $table->string('program_studi');
        $table->string('no_telepon', 20);

        $table->string('berkas_identitas_url');

        $table->enum('status_pendaftaran', [
            'menunggu',
            'dikonfirmasi',
            'ditolak',
            'hadir',
            'tidak_hadir'
        ])->default('menunggu');

        $table->text('catatan_admin')->nullable();
        $table->boolean('email_sent')->default(false);
        $table->foreignId('dikonfirmasi_oleh')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

        $table->timestamp('confirmed_at')->nullable();
        $table->timestamp('email_sent_at')->nullable();

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_tes');
    }
};
