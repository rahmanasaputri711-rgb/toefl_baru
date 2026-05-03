<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('bank_soal', function (Blueprint $table) {
            $table->foreignId('grup_soal_id')
                ->nullable()
                ->after('group_id')
                ->constrained('grup_soal')
                ->nullOnDelete();
        });
    }
    public function down(): void {
        Schema::table('bank_soal', function (Blueprint $table) {
            $table->dropForeign(['grup_soal_id']);
            $table->dropColumn('grup_soal_id');
        });
    }
};
