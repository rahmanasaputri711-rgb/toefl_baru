<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // grup_soal: tambah paket_id + structure ke kategori
        Schema::table('grup_soal', function (Blueprint $table) {
            if (!Schema::hasColumn('grup_soal', 'paket_id'))
                $table->foreignId('paket_id')->nullable()
                      ->constrained('paket_soal')->nullOnDelete()->after('created_by');
        });

        // Alter kategori enum agar include 'structure'
        \DB::statement("ALTER TABLE grup_soal MODIFY COLUMN kategori
            ENUM('listening','reading','structure') NOT NULL DEFAULT 'reading'");

        // passages: tambah image_url (untuk modul email/gambar)
        Schema::table('passages', function (Blueprint $table) {
            if (!Schema::hasColumn('passages', 'image_url'))
                $table->string('image_url', 500)->nullable()->after('teks')
                      ->comment('Path gambar/screenshot email (opsional)');
            if (!Schema::hasColumn('passages', 'modul_id'))
                $table->foreignId('modul_id')->nullable()
                      ->constrained('modul_soal')->nullOnDelete()->after('id');
            if (!Schema::hasColumn('passages', 'paket_id'))
                $table->foreignId('paket_id')->nullable()
                      ->constrained('paket_soal')->nullOnDelete()->after('modul_id');
            if (!Schema::hasColumn('passages', 'grup_id'))
                $table->foreignId('grup_id')->nullable()
                      ->constrained('grup_soal')->nullOnDelete()->after('paket_id');
        });
    }
    public function down(): void {}
};
