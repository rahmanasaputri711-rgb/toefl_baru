<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('bank_soal', function (Blueprint $table) {
            // Part per section: listening=A/B/C, structure=A/B, reading=passage-based
            $table->string('part', 20)->nullable()->after('kategori'); // 'A','B','C'
            // Nomor urut soal dalam part (untuk ordering)
            $table->unsignedSmallInteger('nomor_soal')->nullable()->after('part');
            // Berapa kali dipakai di paket soal
            $table->unsignedInteger('pakai_count')->default(0)->after('is_aktif');
        });
    }
    public function down(): void {
        Schema::table('bank_soal', function (Blueprint $table) {
            $table->dropColumn(['part','nomor_soal','pakai_count']);
        });
    }
};
