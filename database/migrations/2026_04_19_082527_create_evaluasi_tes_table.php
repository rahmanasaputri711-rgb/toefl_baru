<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('evaluasi_tes', function (Blueprint $table) {
        $table->id();

        $table->foreignId('sesi_id')
            ->constrained('sesi_tes')
            ->cascadeOnDelete();

        $table->foreignId('admin_id')
            ->constrained('users')
            ->cascadeOnDelete();

        $table->string('judul');

        $table->text('catatan');

        $table->text('rekomendasi')->nullable();
        $table->boolean('untuk_user')->default(true);
        $table->boolean('is_published')->default(false);

        $table->timestamp('published_at')->nullable();

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi_tes');
    }
};
