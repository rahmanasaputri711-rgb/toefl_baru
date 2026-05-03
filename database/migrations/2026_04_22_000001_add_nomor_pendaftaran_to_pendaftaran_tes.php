<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('pendaftaran_tes', function (Blueprint $table) {
            if (!Schema::hasColumn('pendaftaran_tes','nomor_pendaftaran')) {
                $table->string('nomor_pendaftaran')->nullable()->unique()->after('id');
            }
        });
    }
    public function down(): void {
        Schema::table('pendaftaran_tes', function (Blueprint $table) {
            $table->dropColumn('nomor_pendaftaran');
        });
    }
};
