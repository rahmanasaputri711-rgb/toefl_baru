<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Extend listening_audio_paket:
 * - tipe_upload: 'paket' (1 audio full) atau 'modul' (per modul, digabung di DB)
 * - paket_soal_id: hubungkan ke paket soal
 * - modul_offset_detik: di modul, audio ini mulai di detik ke berapa dalam paket
 * - urutan_modul: urutan modul dalam paket (untuk gabungan)
 */
return new class extends Migration {
    public function up(): void {
        Schema::table('listening_audio_paket', function ($table) {
            if (!Schema::hasColumn('listening_audio_paket', 'tipe_upload'))
                $table->enum('tipe_upload', ['paket','modul'])->default('paket')
                      ->after('tipe_paket')
                      ->comment('paket=1 audio full, modul=per modul lalu digabung');

            if (!Schema::hasColumn('listening_audio_paket', 'paket_soal_id'))
                $table->foreignId('paket_soal_id')->nullable()
                      ->constrained('paket_soal')->nullOnDelete()
                      ->after('tipe_upload');

            if (!Schema::hasColumn('listening_audio_paket', 'urutan_modul'))
                $table->unsignedTinyInteger('urutan_modul')->default(0)
                      ->after('paket_soal_id')
                      ->comment('Urutan modul: Part A=1, Part B=2, dst');

            if (!Schema::hasColumn('listening_audio_paket', 'offset_detik'))
                $table->unsignedSmallInteger('offset_detik')->default(0)
                      ->after('urutan_modul')
                      ->comment('Offset detik dalam gabungan: Part B mulai di detik berapa');

            if (!Schema::hasColumn('listening_audio_paket', 'keterangan'))
                $table->string('keterangan', 200)->nullable()->after('offset_detik');
        });
    }
    public function down(): void {
        Schema::table('listening_audio_paket', fn($t) =>
            $t->dropColumn(['tipe_upload','paket_soal_id','urutan_modul','offset_detik','keterangan']));
    }
};
