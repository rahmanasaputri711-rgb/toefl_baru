<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('paket_soal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->unsignedSmallInteger('jumlah_listening')->default(0);
            $table->unsignedSmallInteger('jumlah_structure')->default(0);
            $table->unsignedSmallInteger('jumlah_reading')->default(0);
            // Status validasi: draft / valid / invalid
            $table->enum('status', ['draft','valid','invalid'])->default('draft');
            $table->boolean('is_aktif')->default(false);
            $table->timestamps();
        });

        // Pivot: soal dalam paket
        Schema::create('paket_soal_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paket_id')->constrained('paket_soal')->cascadeOnDelete();
            $table->foreignId('soal_id')->constrained('bank_soal')->cascadeOnDelete();
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();
            $table->unique(['paket_id','soal_id']); // cegah duplikat
        });
    }
    public function down(): void {
        Schema::dropIfExists('paket_soal_detail');
        Schema::dropIfExists('paket_soal');
    }
};
