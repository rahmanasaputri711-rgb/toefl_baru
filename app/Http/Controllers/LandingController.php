<?php
namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\SesiTes;
use App\Models\Pengumuman;

class LandingController extends Controller
{
    public function index()
    {
        $sesiMendatang = SesiTes::where('tipe_tes', 'full')
            ->where('is_published', 1)
            ->where('waktu_mulai', '>=', now())
            ->orderBy('waktu_mulai')
            ->take(3)->get();

        $pengumuman = Pengumuman::where('is_published', 1)
            ->where(fn($q) => $q->whereNull('expired_at')->orWhere('expired_at', '>', now()))
            ->orderByDesc('is_pinned')
            ->orderByDesc('published_at')
            ->take(3)->get();

        $totalMateri   = Materi::where('is_aktif', 1)->count();

        return view('landing', compact('sesiMendatang', 'pengumuman', 'totalMateri'));
    }
}
