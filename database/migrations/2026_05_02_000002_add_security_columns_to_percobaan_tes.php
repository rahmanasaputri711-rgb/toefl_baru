<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('percobaan_tes', function (Blueprint $table) {
            if (!Schema::hasColumn('percobaan_tes', 'waktu_berakhir'))
                $table->timestamp('waktu_berakhir')->nullable()->after('waktu_mulai')
                      ->comment('Server-side deadline — submit ditolak setelah ini');
            if (!Schema::hasColumn('percobaan_tes', 'status_curang'))
                $table->boolean('status_curang')->default(false)->after('status_sanksi')
                      ->comment('Flag jika terdeteksi kecurangan >= 3 pelanggaran');
            if (!Schema::hasColumn('percobaan_tes', 'reset_count'))
                $table->unsignedTinyInteger('reset_count')->default(0)->after('status_curang')
                      ->comment('Berapa kali admin reset akses tes ini');
        });
    }
    public function down(): void {
        Schema::table('percobaan_tes', function (Blueprint $table) {
            $table->dropColumn(['waktu_berakhir','status_curang','reset_count']);
        });
    }
};
