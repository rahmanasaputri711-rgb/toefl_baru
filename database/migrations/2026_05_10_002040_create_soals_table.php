<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('soals', function (Blueprint $table) {

        $table->id();

        $table->foreignId('passage_id')
              ->constrained()
              ->onDelete('cascade');

        $table->integer('nomor_soal');

        $table->text('pertanyaan');

        $table->string('opsi_a');
        $table->string('opsi_b');
        $table->string('opsi_c');
        $table->string('opsi_d');

        $table->char('jawaban', 1);

        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::table('soals', function (Blueprint $table) {

            $table->dropForeign(['passage_id']);

            $table->dropColumn([
                'passage_id',
                'nomor_soal',
                'pertanyaan',
                'opsi_a',
                'opsi_b',
                'opsi_c',
                'opsi_d',
                'jawaban',
                'tipe_soal',
            ]);
        });
    }
};