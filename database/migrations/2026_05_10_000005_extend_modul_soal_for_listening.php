<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        // Extend tipe_modul enum untuk listening
        DB::statement("ALTER TABLE modul_soal MODIFY COLUMN tipe_modul
            ENUM(
                'passage', 'missing_letters', 'image_email',
                'conversation', 'lecture', 'discussion', 'short_talk'
            ) NOT NULL DEFAULT 'passage'");

        Schema::table('modul_soal', function (Blueprint $table) {
            // FK ke audio paket — semua modul listening dalam 1 grup pakai 1 audio
            if (!Schema::hasColumn('modul_soal', 'audio_paket_id'))
                $table->foreignId('audio_paket_id')->nullable()
                      ->constrained('listening_audio_paket')->nullOnDelete()
                      ->after('tipe_modul')
                      ->comment('Audio full yang dipakai modul ini');
        });
    }
    public function down(): void {
        Schema::table('modul_soal', fn($t) => $t->dropColumn('audio_paket_id'));
        DB::statement("ALTER TABLE modul_soal MODIFY COLUMN tipe_modul
            ENUM('passage','missing_letters','image_email') NOT NULL DEFAULT 'passage'");
    }
};
