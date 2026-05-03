<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PercobaanTes;
use App\Models\LogAdmin;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class ResetAksesTesController extends Controller
{
    public function __construct(private NotificationService $notif) {}

    /** Reset akses tes + perpanjang waktu +15 menit */
    public function reset(Request $request, $percobaanId)
    {
        $percobaan = PercobaanTes::with('user')->findOrFail($percobaanId);

        $tambahanMenit = (int) config('app.reset_tes_tambahan_menit', 15);
        $waktuBaru     = $percobaan->waktu_berakhir
            ? $percobaan->waktu_berakhir->addMinutes($tambahanMenit)
            : now()->addMinutes($tambahanMenit);

        $percobaan->update([
            'status'        => 'berlangsung',
            'waktu_berakhir'=> $waktuBaru,
            'reset_count'   => $percobaan->reset_count + 1,
        ]);

        LogAdmin::create([
            'admin_id'    => auth()->id(),
            'aksi'        => 'reset_tes',
            'target_type' => 'PercobaanTes',
            'target_id'   => $percobaan->id,
            'keterangan'  => "Diperpanjang {$tambahanMenit} menit. Waktu berakhir baru: {$waktuBaru->format('H:i:s')}",
            'ip_address'  => $request->ip(),
        ]);

        if ($percobaan->user) {
            $this->notif->kirimNotifikasi(
                $percobaan->user,
                '🔓 Akses Tes Dipulihkan',
                "Admin telah memulihkan akses tesmu. Waktu tambahan: {$tambahanMenit} menit. Segera lanjutkan!",
                'sukses',
                '/tes/ujian?percobaan_id=' . $percobaan->id . '&section=listening'
            );
        }

        return back()->with('success', "Akses tes direset. Waktu diperpanjang {$tambahanMenit} menit.");
    }

    /** Admin reset cooldown user */
    public function resetCooldown(Request $request, $userId)
    {
        $user = \App\Models\User::findOrFail($userId);
        $user->update(['cooldown_sampai' => null]);

        LogAdmin::create([
            'admin_id'    => auth()->id(),
            'aksi'        => 'reset_cooldown',
            'target_type' => 'User',
            'target_id'   => $userId,
            'keterangan'  => 'Cooldown direset manual oleh admin',
            'ip_address'  => $request->ip(),
        ]);

        return back()->with('success', "Cooldown user {$user->name} berhasil direset.");
    }

    /** Admin reset jumlah absen user */
    public function resetAbsen(Request $request, $userId)
    {
        $user = \App\Models\User::findOrFail($userId);
        $user->update(['jumlah_absen' => 0]);

        LogAdmin::create([
            'admin_id'    => auth()->id(),
            'aksi'        => 'reset_absen',
            'target_type' => 'User',
            'target_id'   => $userId,
            'keterangan'  => 'Jumlah absen direset manual oleh admin',
            'ip_address'  => $request->ip(),
        ]);

        return back()->with('success', "Jumlah absen user {$user->name} direset ke 0.");
    }
}
