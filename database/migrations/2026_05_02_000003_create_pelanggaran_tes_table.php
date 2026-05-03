<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pelanggaran_tes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('percobaan_id')->constrained('percobaan_tes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('jenis', ['tab_switch','copy_paste','klik_kanan','keluar_fullscreen','screenshot','lainnya']);
            $table->unsignedTinyInteger('pelanggaran_ke')->comment('Urutan pelanggaran ke-N');
            $table->string('keterangan')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('waktu_pelanggaran');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pelanggaran_tes'); }
};
