<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\PercobaanTes;
use App\Models\PendaftaranTes;
use App\Models\GrafikProgress;
use App\Models\Materi;
use App\Models\Pengumuman;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $totalTes   = PercobaanTes::where('user_id',$userId)->where('status','selesai')->count();
        $skorTerbaik= PercobaanTes::where('user_id',$userId)->where('status','selesai')->max('skor_total');
        $skorRata   = PercobaanTes::where('user_id',$userId)->where('status','selesai')->avg('skor_total');
        $skorRata   = $skorRata ? round($skorRata) : null;
        $totalMateri= Materi::where('is_aktif',1)->count();

        $tesTerakhir   = PercobaanTes::where('user_id',$userId)->where('status','selesai')->latest('waktu_selesai')->first();
        $pendaftaranAktif = PendaftaranTes::with(['sesiTes'])
            ->where('user_id',$userId)
            ->whereIn('status_pendaftaran',['menunggu','dikonfirmasi'])
            ->latest()->first();

        $pengumuman = Pengumuman::where('is_published',1)
            ->where(fn($q) => $q->whereNull('expired_at')->orWhere('expired_at','>',now()))
            ->orderByDesc('is_pinned')->orderByDesc('published_at')->take(5)->get();

        $grafikData = GrafikProgress::where('user_id',$userId)
            ->whereIn('sumber',['tes_full','tes_simulasi','tes_mini'])
            ->orderBy('tanggal')->take(20)->get();

        return view('user.dashboard.index', compact(
            'totalTes','skorTerbaik','skorRata','totalMateri',
            'tesTerakhir','pendaftaranAktif','pengumuman','grafikData'
        ));
    }
}
