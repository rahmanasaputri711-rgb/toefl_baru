<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('urutan_soal_sesi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('percobaan_id')->constrained('percobaan_tes')->cascadeOnDelete();
            $table->foreignId('soal_id')->constrained('bank_soal')->cascadeOnDelete();
            $table->enum('section', ['listening','structure','reading']);
            $table->unsignedSmallInteger('urutan')->comment('Urutan tampil setelah Fisher-Yates');
            $table->unique(['percobaan_id','soal_id']);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('urutan_soal_sesi'); }
};
