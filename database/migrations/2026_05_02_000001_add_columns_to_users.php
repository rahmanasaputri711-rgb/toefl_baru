<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'cooldown_sampai'))
                $table->timestamp('cooldown_sampai')->nullable()->after('is_active')
                      ->comment('User bisa daftar tes full lagi setelah tanggal ini');
            if (!Schema::hasColumn('users', 'jumlah_absen'))
                $table->unsignedTinyInteger('jumlah_absen')->default(0)->after('cooldown_sampai')
                      ->comment('Jumlah absen tes full tanpa keterangan');
            if (!Schema::hasColumn('users', 'fcm_token'))
                $table->text('fcm_token')->nullable()->after('jumlah_absen')
                      ->comment('Firebase Cloud Messaging token untuk push notification');
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cooldown_sampai','jumlah_absen','fcm_token']);
        });
    }
};
