<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

/**
 * Extend bank_soal untuk mendukung semua tipe soal:
 * Reading  : fill_missing_letters, email_reading, academic_passage (sudah ada)
 * Listening: best_response (sudah ada sebagai multiple_choice)
 * Structure: best_response, arrange_sentence
 */
return new class extends Migration {
    public function up(): void {
        // 1. Extend ENUM tipe_soal dengan tipe baru
        DB::statement("ALTER TABLE bank_soal MODIFY COLUMN tipe_soal
            ENUM(
                'multiple_choice',
                'vocabulary',
                'insert_sentence',
                'click_sentence',
                'prose_summary',
                'fill_missing_letters',
                'email_reading',
                'academic_passage',
                'best_response',
                'arrange_sentence'
            ) NOT NULL DEFAULT 'multiple_choice'
            COMMENT 'Tipe soal: berlaku untuk semua section'
        ");

        Schema::table('bank_soal', function (Blueprint $table) {
            // Untuk arrange_sentence: kata-kata yang perlu disusun (JSON)
            if (!Schema::hasColumn('bank_soal', 'arrange_words'))
                $table->json('arrange_words')->nullable()->after('jawaban_benar_multiple')
                      ->comment('JSON array kata-kata untuk tipe arrange_sentence');

            // Untuk soal dengan gambar (listening best_response)
            if (!Schema::hasColumn('bank_soal', 'image_url'))
                $table->string('image_url', 500)->nullable()->after('arrange_words')
                      ->comment('Gambar opsional untuk soal listening tipe best_response');

            // Untuk email_reading: metadata email
            if (!Schema::hasColumn('bank_soal', 'email_meta'))
                $table->json('email_meta')->nullable()->after('image_url')
                      ->comment('JSON: {sender, recipient, subject, date, body} untuk email_reading');

            // Untuk fill_missing_letters: teks dengan blank
            if (!Schema::hasColumn('bank_soal', 'fill_text'))
                $table->longText('fill_text')->nullable()->after('email_meta')
                      ->comment('Teks dengan blank [___] untuk fill_missing_letters');

            // Nomor urut soal dalam context (reading: per passage, listening: global 1-50)
            if (!Schema::hasColumn('bank_soal', 'urutan_soal'))
                $table->unsignedSmallInteger('urutan_soal')->default(0)->after('nomor_soal')
                      ->comment('Urutan soal dalam passage/paket');
        });
    }

    public function down(): void {
        DB::statement("ALTER TABLE bank_soal MODIFY COLUMN tipe_soal
            ENUM('multiple_choice','vocabulary','insert_sentence','click_sentence','prose_summary')
            NOT NULL DEFAULT 'multiple_choice'
        ");
        Schema::table('bank_soal', fn($t) =>
            $t->dropColumn(['arrange_words','image_url','email_meta','fill_text','urutan_soal']));
    }
};
