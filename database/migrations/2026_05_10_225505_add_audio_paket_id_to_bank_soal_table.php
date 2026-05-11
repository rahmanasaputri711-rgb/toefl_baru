<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bank_soal', function (Blueprint $table) {

            $table->unsignedBigInteger('audio_paket_id')
                ->nullable()
                ->after('paket_id');

        });
    }

    public function down(): void
    {
        Schema::table('bank_soal', function (Blueprint $table) {

            $table->dropColumn('audio_paket_id');

        });
    }
};
