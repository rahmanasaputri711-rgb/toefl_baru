<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Struktur bertingkat:
 * paket_soal → grup_soal → modul_soal → bank_soal
 *
 * modul_soal = satu jenis interaksi dalam satu grup
 * Contoh: "Reading Paket 1 > Group Reading > Modul Passage (soal 5-7)"
 *          "Reading Paket 1 > Group Reading > Modul Missing Letters (soal 8-20)"
 */
return new class extends Migration {
    public function up(): void {

        // Tabel modul_soal
        Schema::create('modul_soal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grup_id')->constrained('grup_soal')->cascadeOnDelete();
            $table->foreignId('paket_id')->constrained('paket_soal')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users');

            // Tipe modul
            $table->enum('tipe_modul', [
                'passage',          // Academic passage + soal
                'missing_letters',  // Fill in the blanks
                'image_email',      // Gambar/screenshot email
            ])->default('passage');

            $table->string('judul', 200)->nullable(); // Label admin
            $table->unsignedSmallInteger('nomor_soal_mulai'); // Soal dimulai dari no berapa
            $table->unsignedSmallInteger('nomor_soal_selesai'); // Soal berakhir di no berapa
            $table->unsignedSmallInteger('urutan')->default(0); // Urutan modul dalam grup
            $table->boolean('is_selesai')->default(false); // Sudah selesai diinput?
            $table->timestamps();
        });

        // Tambah FK modul_id ke passages
        Schema::table('passages', function (Blueprint $table) {
            if (!Schema::hasColumn('passages', 'modul_id'))
                $table->foreignId('modul_id')->nullable()
                      ->constrained('modul_soal')->nullOnDelete()
                      ->after('id');
            if (!Schema::hasColumn('passages', 'paket_id'))
                $table->foreignId('paket_id')->nullable()
                      ->constrained('paket_soal')->nullOnDelete()
                      ->after('modul_id');
        });

        // Tambah FK modul_id + nomor_dalam_paket ke bank_soal
        Schema::table('bank_soal', function (Blueprint $table) {
            if (!Schema::hasColumn('bank_soal', 'modul_id'))
                $table->foreignId('modul_id')->nullable()
                      ->constrained('modul_soal')->nullOnDelete()
                      ->after('passage_id');
            if (!Schema::hasColumn('bank_soal', 'paket_id'))
                $table->foreignId('paket_id')->nullable()
                      ->constrained('paket_soal')->nullOnDelete()
                      ->after('modul_id');
            if (!Schema::hasColumn('bank_soal', 'nomor_dalam_paket'))
                $table->unsignedSmallInteger('nomor_dalam_paket')->default(0)
                      ->after('paket_id')
                      ->comment('Nomor soal global dalam paket (1, 2, 3, ...)');
        });
    }

    public function down(): void {
        Schema::table('bank_soal', fn($t) =>
            $t->dropColumn(['modul_id','paket_id','nomor_dalam_paket']));
        Schema::table('passages', fn($t) =>
            $t->dropColumn(['modul_id','paket_id']));
        Schema::dropIfExists('modul_soal');
    }
};
