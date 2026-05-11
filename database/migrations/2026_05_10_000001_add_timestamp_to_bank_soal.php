<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('bank_soal', function (Blueprint $table) {
            if (!Schema::hasColumn('bank_soal', 'start_second'))
               $table->unsignedSmallInteger('start_second')
      ->default(0)
      ->after('tipe_soal')
      ->comment('Detik ke berapa soal ini muncul dalam audio (timeline)');
            if (!Schema::hasColumn('bank_soal', 'order_number'))
                $table->unsignedTinyInteger('order_number')->default(0)->after('start_second')
                      ->comment('Urutan tampil soal (1-50), wajib berurutan sesuai audio');
        });
    }
    public function down(): void {
        Schema::table('bank_soal', fn($t) =>
            $t->dropColumn(['start_second', 'order_number']));
    }
};
