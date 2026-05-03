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
       Schema::create('pengumuman', function (Blueprint $table) {
        $table->id();

        $table->foreignId('admin_id')
            ->constrained('users')
            ->cascadeOnDelete();

        $table->string('judul');
        $table->text('konten');

        $table->enum('target', ['semua','user'])->default('semua');

        $table->boolean('is_published')->default(false);
        $table->boolean('is_pinned')->default(false);
        $table->timestamp('published_at')->nullable();
        $table->timestamp('expired_at')->nullable();

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumuman');
    }
};
