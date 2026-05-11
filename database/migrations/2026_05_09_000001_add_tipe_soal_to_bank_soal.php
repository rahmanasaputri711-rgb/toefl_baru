<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tambah kolom tipe_soal untuk mendukung format Reading iBT:
 * - multiple_choice  : pilih 1 dari 4 (default)
 * - vocabulary       : kata di-highlight, pilih sinonim/makna
 * - insert_sentence  : pilih posisi kalimat (A/B/C/D di dalam teks)
 * - click_sentence   : klik kalimat langsung di teks passage
 * - prose_summary    : pilih 3 dari 6 pernyataan yang benar (drag & drop)
 */
return new class extends Migration {
    public function up(): void {
        Schema::table('bank_soal', function (Blueprint $table) {
            if (!Schema::hasColumn('bank_soal', 'tipe_soal'))
                $table->enum('tipe_soal', [
                    'multiple_choice',
                    'vocabulary',
                    'insert_sentence',
                    'click_sentence',
                    'prose_summary',
                ])->default('multiple_choice')->after('kategori')
                  ->comment('Tipe soal untuk format iBT Reading');

            // Untuk vocabulary: simpan posisi kata yang di-highlight di teks
            if (!Schema::hasColumn('bank_soal', 'highlight_kata'))
                $table->string('highlight_kata', 100)->nullable()->after('tipe_soal')
                      ->comment('Kata yang di-highlight di passage (tipe vocabulary)');

            if (!Schema::hasColumn('bank_soal', 'highlight_paragraf'))
                $table->unsignedTinyInteger('highlight_paragraf')->nullable()->after('highlight_kata')
                      ->comment('Nomor paragraf tempat kata di-highlight');

            // Untuk insert_sentence: kalimat yang harus disisipkan
            if (!Schema::hasColumn('bank_soal', 'insert_sentence_teks'))
                $table->text('insert_sentence_teks')->nullable()->after('highlight_paragraf')
                      ->comment('Kalimat yang perlu disisipkan (tipe insert_sentence)');

            // Untuk prose_summary: pilihan A-F, benar bisa lebih dari 1
            if (!Schema::hasColumn('bank_soal', 'jawaban_benar_multiple'))
                $table->string('jawaban_benar_multiple', 20)->nullable()->after('jawaban_benar')
                      ->comment('Jawaban benar multiple, contoh: a,c,e (untuk prose_summary)');

            // Pilihan E dan F untuk prose_summary (ada 6 pilihan)
            if (!Schema::hasColumn('bank_soal', 'pilihan_e'))
                $table->text('pilihan_e')->nullable()->after('pilihan_d');
            if (!Schema::hasColumn('bank_soal', 'pilihan_f'))
                $table->text('pilihan_f')->nullable()->after('pilihan_e');
        });
    }

    public function down(): void {
        Schema::table('bank_soal', function (Blueprint $table) {
            $table->dropColumn([
                'tipe_soal','highlight_kata','highlight_paragraf',
                'insert_sentence_teks','jawaban_benar_multiple',
                'pilihan_e','pilihan_f',
            ]);
        });
    }
};
