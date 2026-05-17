<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('bank_soal', function (Blueprint $table) {
            // Timeline listening
            if (!Schema::hasColumn('bank_soal', 'audio_end'))
                $table->unsignedSmallInteger('audio_end')->default(0)
                      ->after('start_second')
                      ->comment('Detik audio berhenti (end of conversation)');

            if (!Schema::hasColumn('bank_soal', 'pause_duration'))
                $table->unsignedTinyInteger('pause_duration')->default(15)
                      ->after('audio_end')
                      ->comment('Durasi pause untuk menjawab (detik)');

            if (!Schema::hasColumn('bank_soal', 'session_resume_time'))
                $table->unsignedSmallInteger('session_resume_time')->default(0)
                      ->after('pause_duration')
                      ->comment('Virtual timeline: detik saat audio lanjut setelah pause');
        });
    }
    public function down(): void {
        Schema::table('bank_soal', fn($t) =>
            $t->dropColumn(['audio_end', 'pause_duration', 'session_resume_time']));
    }
};
