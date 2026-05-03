<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PercobaanTes;
use App\Models\JawabanMahasiswa;
use App\Models\EvaluasiTes;
use App\Models\PendaftaranTes;
use App\Models\GrafikProgress;

class HasilController extends Controller
{
    /**
     * Riwayat semua tes milik user (semua tipe: full, mini, simulasi)
     */
    public function index()
    {
        $userId = auth()->id();

        $percobaan = PercobaanTes::with('sesiTes')
            ->where('user_id', $userId)
            ->where('status', 'selesai')
            ->latest('waktu_selesai')
            ->paginate(15);

        // Data grafik: ambil dari GrafikProgress (sumber tes_full saja)
        $grafik = GrafikProgress::where('user_id', $userId)
            ->orderBy('tanggal')
            ->orderBy('created_at')
            ->get();

        // Hitung statistik ringkas
        $tesSelesai = PercobaanTes::where('user_id', $userId)
            ->where('status', 'selesai')->get();

        $stats = [
            'total_tes'   => $tesSelesai->count(),
            'skor_max'    => $tesSelesai->max('skor_total') ?? 0,
            'skor_min'    => $tesSelesai->min('skor_total') ?? 0,
            'skor_rata'   => $tesSelesai->count()
                ? (int) round($tesSelesai->avg('skor_total'))
                : 0,
        ];

        return view('user.hasil.index', compact('percobaan', 'grafik', 'stats'));
    }

    /**
     * Detail skor + review jawaban satu percobaan
     */
    public function detail($id)
    {
        $userId = auth()->id();

        // Cari percobaan — status boleh 'selesai' saja (aman)
        $percobaan = PercobaanTes::with('sesiTes')
            ->where('user_id', $userId)
            ->whereIn('status', ['selesai'])
            ->findOrFail($id);

        // Jawaban: pakai 'jawaban_dipilih' (nama kolom sesungguhnya)
        $jawaban = JawabanMahasiswa::with('soal')
            ->where('percobaan_id', $id)
            ->orderByRaw("FIELD(soal_id, (SELECT soal_id FROM jawaban_mahasiswa WHERE percobaan_id = $id ORDER BY id LIMIT 1))")
            ->get()
            ->sortBy(fn($j) => $j->soal?->kategori . '_' . $j->id);

        $evaluasi = EvaluasiTes::where('sesi_id', $percobaan->sesi_id)
            ->where('is_published', 1)->first();

        // Tampilkan pembahasan jika sesi mengijinkan
        $tampilPembahasan = $percobaan->sesiTes?->tampilkan_pembahasan ?? false;

        return view('user.hasil.detail', compact(
            'percobaan', 'jawaban', 'evaluasi', 'tampilPembahasan'
        ));
    }

    /**
     * Cetak kartu/PDF hasil tes
     */
    public function cetak($id)
    {
        $userId = auth()->id();

        $percobaan = PercobaanTes::with('sesiTes')
            ->where('user_id', $userId)
            ->where('status', 'selesai')
            ->findOrFail($id);

        $user        = auth()->user();
        $pendaftaran = PendaftaranTes::where('user_id', $userId)
            ->where('sesi_id', $percobaan->sesi_id)
            ->first();

        // Hitung urutan tes user ini (sudah ke berapa kali)
        $tesKe = $percobaan->tes_ke ?? PercobaanTes::where('user_id', $userId)
            ->where('id', '<=', $id)
            ->where('status', 'selesai')
            ->count();

        return view('user.hasil.cetak', compact(
            'percobaan', 'user', 'pendaftaran', 'tesKe'
        ));
    }
}
