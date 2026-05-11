<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('passages', function (Blueprint $table) {

            $table->id();

            $table->string('judul');

            $table->longText('teks');

            $table->enum('tipe_paket', [
                'praktik',
                'mini',
                'simulasi',
                'full'
            ]);

            $table->foreignId('created_by')
                  ->constrained('users');

            $table->boolean('is_aktif')->default(true);

            $table->integer('urutan')->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('passages');
    }
};