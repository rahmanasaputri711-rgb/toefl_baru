<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * FIX: User yang role-nya NULL/kosong tidak muncul di daftar user admin
 * karena filter where('role','user').
 * Migration ini set role='user' untuk semua user yang role-nya null/kosong.
 */
return new class extends Migration {
    public function up(): void {
        // Set role='user' untuk semua yang role-nya null atau kosong (bukan admin)
        DB::table('users')
            ->whereNull('role')
            ->orWhere('role', '')
            ->update(['role' => 'user']);

        // Pastikan is_active = 0 untuk user yang belum punya konfirmasi pendaftaran
        // (aman: user yang sudah dikonfirmasi sudah is_active = 1)
    }

    public function down(): void {}
};
