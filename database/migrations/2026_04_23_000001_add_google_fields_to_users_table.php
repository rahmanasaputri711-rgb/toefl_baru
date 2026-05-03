<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * FIX: Migration ini adalah duplikat dari 2026_04_22_000002.
 * Ditambahkan hasColumn() check agar tidak crash jika kolom sudah ada.
 */
return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'google_id'))
                $table->string('google_id')->nullable()->after('id');
            if (!Schema::hasColumn('users', 'avatar'))
                $table->string('avatar')->nullable()->after('google_id');
        });
    }
    public function down(): void {}
};
