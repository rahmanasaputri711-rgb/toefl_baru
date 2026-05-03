<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AktivitasLog;
use App\Models\BankSoal;
use App\Models\PendaftaranTes;
use App\Models\PercobaanTes;
use App\Models\SesiTes;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUser      = User::where('role', 'user')->where('is_active', 1)->count();
        $pendingVerif   = User::where('role', 'user')->where('is_active', 0)->count();
        $totalSoal      = BankSoal::where('is_aktif', 1)->count();
        $sesiAktif      = SesiTes::where('is_aktif', 1)->count();
        $pelanggaran    = AktivitasLog::whereDate('created_at', today())->count();

        $pendaftaranTerbaru = PendaftaranTes::with(['user', 'sesiTes'])
            ->latest()->take(5)->get();

        $sesiMendatang = SesiTes::where('waktu_mulai', '>=', now())
            ->orderBy('waktu_mulai')->take(5)->get();

        $aktivitasLog = AktivitasLog::with('user')
            ->whereNotNull('pelanggaran_ke')
            ->latest()->take(10)->get();

        return view('admin.dashboard.index', compact(
            'totalUser', 'pendingVerif', 'totalSoal',
            'sesiAktif', 'pelanggaran',
            'pendaftaranTerbaru', 'sesiMendatang', 'aktivitasLog'
        ));
    }
}
