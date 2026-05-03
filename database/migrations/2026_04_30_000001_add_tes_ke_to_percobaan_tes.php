<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::table('percobaan_tes', function (Blueprint $table) {
            // Percobaan ke-N untuk user ini (1=pertama, 2=kedua, dst)
            $table->unsignedTinyInteger('tes_ke')->default(1)->after('sesi_id');
        });

        // Backfill tes_ke untuk data existing
        $rows = DB::table('percobaan_tes')
            ->select('id','user_id','created_at')
            ->orderBy('user_id')->orderBy('created_at')
            ->get();

        $counter = [];
        foreach ($rows as $row) {
            $counter[$row->user_id] = ($counter[$row->user_id] ?? 0) + 1;
            DB::table('percobaan_tes')
                ->where('id', $row->id)
                ->update(['tes_ke' => $counter[$row->user_id]]);
        }
    }

    public function down(): void {
        Schema::table('percobaan_tes', function (Blueprint $table) {
            $table->dropColumn('tes_ke');
        });
    }
};
