<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        // Tambah nilai 'selesai' ke ENUM status_pendaftaran
        DB::statement("ALTER TABLE pendaftaran_tes 
            MODIFY COLUMN status_pendaftaran 
            ENUM('menunggu','dikonfirmasi','ditolak','hadir','tidak_hadir','selesai','dibatalkan')
            NOT NULL DEFAULT 'menunggu'");
    }
    public function down(): void {
        DB::statement("ALTER TABLE pendaftaran_tes 
            MODIFY COLUMN status_pendaftaran 
            ENUM('menunggu','dikonfirmasi','ditolak','hadir','tidak_hadir')
            NOT NULL DEFAULT 'menunggu'");
    }
};
