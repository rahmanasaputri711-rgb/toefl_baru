<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('pendaftaran_tes', function (Blueprint $table) {
            if (!Schema::hasColumn('pendaftaran_tes', 'dibatalkan_at'))
                $table->timestamp('dibatalkan_at')->nullable()->after('confirmed_at');
            if (!Schema::hasColumn('pendaftaran_tes', 'alasan_batal'))
                $table->string('alasan_batal')->nullable()->after('dibatalkan_at');
            if (!Schema::hasColumn('pendaftaran_tes', 'is_hadir'))
                $table->boolean('is_hadir')->nullable()->after('alasan_batal')
                      ->comment('null=belum, true=hadir, false=absen');
            if (!Schema::hasColumn('pendaftaran_tes', 'ditandai_absen_at'))
                $table->timestamp('ditandai_absen_at')->nullable()->after('is_hadir');
        });
    }
    public function down(): void {
        Schema::table('pendaftaran_tes', function (Blueprint $table) {
            $table->dropColumn(['dibatalkan_at','alasan_batal','is_hadir','ditandai_absen_at']);
        });
    }
};
