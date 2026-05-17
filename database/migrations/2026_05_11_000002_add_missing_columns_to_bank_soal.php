<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('bank_soal', function (Blueprint $table) {
            if (!Schema::hasColumn('bank_soal', 'audio_script'))
                $table->text('audio_script')->nullable()->after('audio_url');

            if (!Schema::hasColumn('bank_soal', 'modul_id'))
                $table->foreignId('modul_id')->nullable()
                      ->constrained('modul_soal')->nullOnDelete()->after('id');

            if (!Schema::hasColumn('bank_soal', 'paket_id'))
                $table->foreignId('paket_id')->nullable()
                      ->constrained('paket_soal')->nullOnDelete()->after('modul_id');

            if (!Schema::hasColumn('bank_soal', 'passage_id'))
                $table->foreignId('passage_id')->nullable()
                      ->after('paket_id');

            if (!Schema::hasColumn('bank_soal', 'grup_soal_id'))
                $table->unsignedBigInteger('grup_soal_id')->nullable()->after('passage_id');

            if (!Schema::hasColumn('bank_soal', 'part'))
                $table->string('part', 10)->nullable()->after('kategori');

            if (!Schema::hasColumn('bank_soal', 'sub_bagian'))
                $table->string('sub_bagian', 50)->nullable()->after('part');

            if (!Schema::hasColumn('bank_soal', 'nomor_soal'))
                $table->unsignedSmallInteger('nomor_soal')->default(0)->after('sub_bagian');

            if (!Schema::hasColumn('bank_soal', 'tipe_paket'))
                $table->string('tipe_paket', 20)->default('full')->after('nomor_soal');

            if (!Schema::hasColumn('bank_soal', 'skill_materi'))
                $table->string('skill_materi', 100)->nullable()->after('tipe_paket');

            if (!Schema::hasColumn('bank_soal', 'tipe_soal'))
                $table->string('tipe_soal', 50)->default('multiple_choice')->after('skill_materi');

            if (!Schema::hasColumn('bank_soal', 'fill_text'))
                $table->longText('fill_text')->nullable()->after('tipe_soal');

            if (!Schema::hasColumn('bank_soal', 'email_meta'))
                $table->json('email_meta')->nullable()->after('fill_text');

            if (!Schema::hasColumn('bank_soal', 'image_url'))
                $table->string('image_url', 500)->nullable()->after('email_meta');

            if (!Schema::hasColumn('bank_soal', 'arrange_words'))
                $table->json('arrange_words')->nullable()->after('image_url');

            if (!Schema::hasColumn('bank_soal', 'highlight_kata'))
                $table->string('highlight_kata', 100)->nullable()->after('arrange_words');

            if (!Schema::hasColumn('bank_soal', 'highlight_paragraf'))
                $table->unsignedTinyInteger('highlight_paragraf')->nullable()->after('highlight_kata');

            if (!Schema::hasColumn('bank_soal', 'insert_sentence_teks'))
                $table->text('insert_sentence_teks')->nullable()->after('highlight_paragraf');

            if (!Schema::hasColumn('bank_soal', 'pilihan_e'))
                $table->text('pilihan_e')->nullable()->after('pilihan_d');

            if (!Schema::hasColumn('bank_soal', 'pilihan_f'))
                $table->text('pilihan_f')->nullable()->after('pilihan_e');

            if (!Schema::hasColumn('bank_soal', 'jawaban_benar_multiple'))
                $table->string('jawaban_benar_multiple', 20)->nullable()->after('jawaban_benar');

            if (!Schema::hasColumn('bank_soal', 'rationale'))
                $table->text('rationale')->nullable()->after('pembahasan');

            if (!Schema::hasColumn('bank_soal', 'group_id'))
                $table->string('group_id', 50)->nullable()->after('rationale');

            if (!Schema::hasColumn('bank_soal', 'pakai_count'))
                $table->unsignedSmallInteger('pakai_count')->default(0)->after('group_id');

            if (!Schema::hasColumn('bank_soal', 'urutan_soal'))
                $table->unsignedSmallInteger('urutan_soal')->default(0);
        });
    }

    public function down(): void {}
};
