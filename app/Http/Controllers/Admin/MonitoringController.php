<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\PercobaanTes;
use App\Models\PelanggaranTes;
use App\Models\SesiTes;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $sesiAktif = SesiTes::where('is_aktif',1)->get();
        $sesi_id   = $request->sesi_id ?? $sesiAktif->first()?->id;

        $pesertaAktif = PercobaanTes::with(['user','pelanggaran'])
            ->where('status','berlangsung')
            ->when($sesi_id, fn($q) => $q->where('sesi_id',$sesi_id))
            ->orderByDesc('last_autosave_at')->get()
            ->map(function($p) {
                $p->online = $p->last_autosave_at &&
                    Carbon::parse($p->last_autosave_at)->diffInSeconds(now()) < 90;
                $p->sisa_detik = $p->waktu_berakhir
                    ? max(0, now()->diffInSeconds($p->waktu_berakhir, false))
                    : null;
                $p->total_percobaan = PercobaanTes::where('user_id',$p->user_id)->count();
                return $p;
            });

        $logPelanggaran = PelanggaranTes::with(['user','percobaan'])
            ->when($sesi_id, fn($q) => $q->whereHas('percobaan', fn($q2) => $q2->where('sesi_id',$sesi_id)))
            ->latest()->take(50)->get();

        // Statistik ringkas sesi ini
        $statsAktif = [
            'sedang'   => $pesertaAktif->count(),
            'online'   => $pesertaAktif->where('online',true)->count(),
            'offline'  => $pesertaAktif->where('online',false)->count(),
            'curang'   => $pesertaAktif->where('status_curang',true)->count(),
        ];

        return view('admin.monitoring.index', compact(
            'sesiAktif','sesi_id','pesertaAktif','logPelanggaran','statsAktif'
        ));
    }

    /** Diskualifikasi langsung dari monitoring */
    public function diskualifikasi(Request $request, $percobaanId)
    {
        $percobaan = PercobaanTes::with('user')->findOrFail($percobaanId);

        $percobaan->update([
            'status'        => 'dibatalkan',
            'status_curang' => true,
            'status_sanksi' => 'diskualifikasi_admin',
        ]);

        if ($percobaan->user) {
            $percobaan->user->update(['is_active' => 0]);
            Notifikasi::create([
                'user_id'      => $percobaan->user_id,
                'judul'        => '🚫 Tes Didiskualifikasi',
                'pesan'        => 'Tes kamu didiskualifikasi oleh admin UPA Bahasa. Hubungi kami untuk informasi lebih lanjut.',
                'tipe'         => 'danger',
                'is_important' => 1,
            ]);
        }

        \App\Models\LogAdmin::create([
            'admin_id'    => auth()->id(),
            'aksi'        => 'diskualifikasi',
            'target_type' => 'PercobaanTes',
            'target_id'   => $percobaanId,
            'keterangan'  => $request->alasan ?? 'Didiskualifikasi dari halaman monitoring',
            'ip_address'  => $request->ip(),
        ]);

        return back()->with('success', "Peserta {$percobaan->user?->name} berhasil didiskualifikasi.");
    }

    public function nonaktifkanUser($userId)
    {
        \App\Models\User::findOrFail($userId)->update(['is_active' => 0]);
        PercobaanTes::where('user_id',$userId)->where('status','berlangsung')
            ->update(['status' => 'dibatalkan']);
        Notifikasi::create([
            'user_id'=>$userId,'judul'=>'Akun Dinonaktifkan',
            'pesan'=>'Akun Anda dinonaktifkan oleh admin karena pelanggaran.','tipe'=>'danger','is_important'=>1,
        ]);
        return back()->with('success','User berhasil dinonaktifkan.');
    }
}
