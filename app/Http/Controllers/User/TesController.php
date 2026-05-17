<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BankSoal;
use App\Models\PendaftaranTes;
use App\Models\PercobaanTes;
use App\Models\PelanggaranTes;
use App\Models\UrutanSoalSesi;
use App\Models\SesiTes;
use App\Models\SesiTesSoal;
use App\Models\JawabanMahasiswa;
use App\Models\GrafikProgress;
use App\Models\Notifikasi;
use App\Services\FisherYatesService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TesController extends Controller
{
    // ─── DURASI PER SECTION (detik) — standar TOEFL ITP ─────────
    const DURASI = [
        'listening' => 2100,  // 35 menit
        'structure' => 1500,  // 25 menit
        'reading'   => 3300,  // 55 menit
    ];

    // ─── HALAMAN TES FULL ────────────────────────────────────────
    public function full()
    {
        $userId = auth()->id();
        $user   = auth()->user();

        // Cek blokir absen (tetap ada — ini sanksi ketidakhadiran, bukan cooldown)
        if ($user->diblokir()) {
            return view('user.tes.full', [
                'pendaftaran'     => null,
                'sesiAktif'       => null,
                'sesiList'        => collect(),
                'blokirAbsen'     => true,
                'sudahLulus'      => false,
                'jumlahTes'       => 0,
                'sisaCoba'        => 0,
                'sesiSudahDaftar' => [],
                'sesiSudahSelesai'=> [],
            ]);
        }

        // Hitung riwayat tes full yang sudah selesai
        $riwayatTes = PercobaanTes::where('user_id', $userId)
            ->where('status', 'selesai')
            ->whereHas('sesiTes', fn($q) => $q->where('tipe_tes','full'))
            ->orderBy('created_at')
            ->get();

        $jumlahTes  = $riwayatTes->count();
        $maxCoba    = 3;
        $sisaCoba   = max(0, $maxCoba - $jumlahTes);

        // Cek apakah sudah lulus (skor >= 500 di salah satu tes)
        $sudahLulus = $riwayatTes->contains(fn($p) => $p->skor_total >= 500);

        // Jika sudah lulus → tidak bisa tes lagi
        if ($sudahLulus) {
            $tesTerbaik = $riwayatTes->sortByDesc('skor_total')->first();
            return view('user.tes.full', [
                'pendaftaran'     => null,
                'sesiAktif'       => null,
                'sesiList'        => collect(),
                'sudahLulus'      => true,
                'jumlahTes'       => $jumlahTes,
                'sisaCoba'        => 0,
                'tesTerbaik'      => $tesTerbaik,
                'blokirAbsen'     => false,
                'sesiSudahDaftar' => [],
                'sesiSudahSelesai'=> [],
            ]);
        }

        // Jika sudah 3x gagal → tidak bisa tes lagi
        if ($jumlahTes >= $maxCoba) {
            $tesTerakhir = $riwayatTes->last();
            return view('user.tes.full', [
                'pendaftaran'     => null,
                'sesiAktif'       => null,
                'sesiList'        => collect(),
                'sudahLulus'      => false,
                'maxTercapai'     => true,
                'jumlahTes'       => $jumlahTes,
                'sisaCoba'        => 0,
                'tesTerakhir'     => $tesTerakhir,
                'blokirAbsen'     => false,
                'sesiSudahDaftar' => [],
                'sesiSudahSelesai'=> [],
            ]);
        }

        // Normal: cek pendaftaran aktif
        $pendaftaran = PendaftaranTes::with('sesiTes')
            ->where('user_id', $userId)
            ->whereIn('status_pendaftaran', ['menunggu','dikonfirmasi'])
            ->latest()->first();

        $sesiAktif = null;
        if ($pendaftaran && $pendaftaran->status_pendaftaran === 'dikonfirmasi') {
            $sesiAktif = SesiTes::where('id', $pendaftaran->sesi_id)
                ->where('is_aktif', 1)->first();
        }

        $sesiList = SesiTes::where('tipe_tes', 'full')
            ->where('is_published', 1)
            ->whereRaw('peserta_terdaftar < kuota_peserta')
            ->orderBy('waktu_mulai')->get();

        // Hanya ambil sesi_id yang pendaftarannya MASIH AKTIF (menunggu/dikonfirmasi)
        // Sesi yang sudah selesai/ditolak/dibatalkan TIDAK ditandai "Sudah Ada Pendaftaran"
        // karena user boleh daftar ke sesi baru setelah tes lama selesai
        // Sesi yang pendaftarannya masih aktif (menunggu/dikonfirmasi)
        $sesiSudahDaftar = \App\Models\PendaftaranTes::where('user_id', $userId)
            ->whereIn('status_pendaftaran', ['menunggu','dikonfirmasi'])
            ->pluck('sesi_id')->toArray();

        // Sesi yang sudah pernah DISELESAIKAN tes-nya
        // → tombol Mulai Tes disembunyikan, user harus daftar sesi LAIN
        $sesiSudahSelesai = PercobaanTes::where('user_id', $userId)
            ->where('status', 'selesai')
            ->whereNotNull('sesi_id')
            ->pluck('sesi_id')->toArray();

        return view('user.tes.full', compact(
            'pendaftaran','sesiAktif','sesiList',
            'jumlahTes','sisaCoba','sudahLulus',
            'sesiSudahDaftar','sesiSudahSelesai'
        ) + [
            'blokirAbsen' => false,
            'maxTercapai' => false,
            'tesTerbaik'  => null,
            'tesTerakhir' => null,
        ]);
    }

    // ─── MULAI TES FULL ──────────────────────────────────────────
    public function mulai(Request $request)
    {
        $userId = auth()->id();
        $user   = auth()->user();

        // Cek blokir absen
        if ($user->diblokir())
            return back()->with('error', 'Akunmu dibekukan sementara karena 3x tidak hadir. Hubungi UPA Bahasa.');

        // Cek sudah lulus
        $sudahLulus = PercobaanTes::where('user_id', $userId)
            ->where('status','selesai')
            ->whereHas('sesiTes', fn($q) => $q->where('tipe_tes','full'))
            ->where('skor_total', '>=', 500)->exists();
        if ($sudahLulus)
            return back()->with('error', 'Kamu sudah lulus tes TOEFL ITP. Tidak perlu mengulang.');

        // Cek maksimum 3 kali
        $jumlahTes = PercobaanTes::where('user_id', $userId)
            ->where('status','selesai')
            ->whereHas('sesiTes', fn($q) => $q->where('tipe_tes','full'))
            ->count();
        if ($jumlahTes >= 3)
            return back()->with('error', 'Kamu sudah mencapai batas maksimal 3 kali tes full.');

        $sesi = SesiTes::where('id', $request->sesi_id)
            ->where('is_aktif', 1)->firstOrFail();

        // Validasi 1: Harus ada pendaftaran dikonfirmasi untuk sesi ini
        $pendaftaran = PendaftaranTes::where('user_id', $userId)
            ->where('sesi_id', $sesi->id)
            ->where('status_pendaftaran', 'dikonfirmasi')->first();

        if (!$pendaftaran)
            return back()->with('error',
                'Kamu belum mendaftar atau pendaftaran belum di-ACC admin untuk sesi ini.');

        // Validasi 2: Akun harus aktif (diaktifkan saat ACC pendaftaran)
        if (!(bool) $user->is_active)
            return back()->with('error',
                'Akun belum diaktifkan. Hubungi admin UPA Bahasa.');

        // Cek apakah sudah ada percobaan berlangsung (resume)
        $existing = PercobaanTes::where('user_id', $userId)
            ->where('sesi_id', $sesi->id)
            ->where('status', 'berlangsung')->first();

        if ($existing) {
            if ($existing->waktuHabis()) {
                $this->selesaikan($existing);
                return redirect()->route('user.hasil.detail', $existing->id);
            }
            // Resume section terakhir yang dikerjakan
            $lastSection = $existing->last_section ?? 'listening';
            return redirect()->route('user.tes.ujian', [
                'percobaan_id' => $existing->id,
                'section'      => $lastSection,
            ]);
        }

        // Cek apakah sudah pernah selesai tes di sesi INI
        // Jika iya → harus daftar sesi LAIN, tidak bisa tes di sesi sama lagi
        $sudahSelesaiDiSesiIni = PercobaanTes::where('user_id', $userId)
            ->where('sesi_id', $sesi->id)
            ->where('status', 'selesai')->exists();

        if ($sudahSelesaiDiSesiIni) {
            return back()->with('error',
                'Kamu sudah pernah mengikuti tes di sesi ini. '.
                'Daftar ke jadwal sesi lain untuk mengulang tes.');
        }

        // Buat percobaan baru
        $durasiTotal = array_sum(self::DURASI);
        // Bersihkan session tes lama
        session()->forget(['tes_berlangsung_id']);

        $percobaan   = PercobaanTes::create([
            'user_id'         => $userId,
            'sesi_id'         => $sesi->id,
            'tes_ke'          => PercobaanTes::where('user_id', $userId)->count() + 1,
            'waktu_mulai'     => now(),
            'waktu_berakhir'  => now()->addSeconds($durasiTotal + 120), // +2 menit buffer
            'status'          => 'berlangsung',
            'ip_address'      => $request->ip(),
            'browser_info'    => substr($request->userAgent(), 0, 255),
        ]);

        // Simpan urutan soal Fisher-Yates per section ke DB
        $this->simpanUrutanSoal($percobaan, $sesi->id);
        $this->initJawaban($percobaan, 'listening');

        return redirect()->route('user.tes.ujian', [
            'percobaan_id' => $percobaan->id,
            'section'      => 'listening',
        ]);
    }

    // ─── SIMPAN URUTAN SOAL FISHER-YATES KE DB ───────────────────
    private function simpanUrutanSoal(PercobaanTes $percobaan, int $sesiId): void
    {
        // Hapus dulu jika ada (resume)
        UrutanSoalSesi::where('percobaan_id', $percobaan->id)->delete();

        $rows = [];
        $now  = now();

        foreach (['listening', 'structure', 'reading'] as $section) {
            $soalIds = SesiTesSoal::where('sesi_id', $sesiId)
                ->where('bagian', $section)
                ->pluck('soal_id')
                ->toArray();

            if (empty($soalIds)) continue;

            // Fisher-Yates per section
            $shuffled = FisherYatesService::shuffleSoal($soalIds, $section, false);

            foreach ($shuffled as $idx => $soalId) {
                $rows[] = [
                    'percobaan_id' => $percobaan->id,
                    'soal_id'      => $soalId,
                    'section'      => $section,
                    'urutan'       => $idx + 1,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ];
            }
        }

        // Bulk insert — jauh lebih cepat dari 140x updateOrCreate
        if (!empty($rows)) {
            \DB::table('urutan_soal_sesi')->insert($rows);
        }
    }

    // ─── HALAMAN UJIAN ───────────────────────────────────────────
    public function ujian(Request $request)
    {
        $userId      = auth()->id();
        $percobaanId = $request->percobaan_id;
        $section     = $request->section ?? 'listening';

        $percobaan = PercobaanTes::with('sesiTes')
            ->where('id', $percobaanId)
            ->where('user_id', $userId)
            ->where('status', 'berlangsung')->firstOrFail();

        // SERVER-SIDE: cek waktu habis
        if ($percobaan->waktuHabis()) {
            $this->selesaikan($percobaan);
            return redirect()->route('user.hasil.detail', $percobaan->id)
                ->with('info', 'Waktu tes telah habis. Jawaban otomatis dikumpulkan.');
        }

        $sectionNum  = ['listening' => 1, 'structure' => 2, 'reading' => 3];
        $durasiDetik = self::DURASI[$section] ?? 2100;

        // Ambil soal sesuai urutan Fisher-Yates yang sudah disimpan
        $soalList = UrutanSoalSesi::with('soal')
            ->where('percobaan_id', $percobaan->id)
            ->where('section', $section)
            ->orderBy('urutan')
            ->get()
            ->filter(fn($u) => $u->soal && $u->soal->pertanyaan)
            ->map(function ($u) {
                $soal = $u->soal;
                // KEAMANAN: sembunyikan kunci jawaban dari front-end
                $soal->makeHidden(['jawaban_benar','pembahasan']);
                $soal->audio_url_resolved = \App\Services\AudioService::resolveUrl($soal->audio_url);
                return $u;
            });

        $pendaftaran = PendaftaranTes::where('user_id', $userId)
            ->where('sesi_id', $percobaan->sesi_id)->first();

        $jawabanTersimpan = JawabanMahasiswa::where('percobaan_id', $percobaanId)
            ->whereHas('soal', fn($q) => $q->where('kategori', $section))
            ->pluck('jawaban_dipilih', 'soal_id')
            ->toArray();

        $currentSection  = $section;
        $waktuBerakhir   = $percobaan->waktu_berakhir;

        // Splash HANYA muncul saat PERTAMA KALI (section=listening & belum ada jawaban)
        // Pindah section (structure/reading) = TIDAK tampil splash, langsung lanjut
        $sudahAdaJawaban = \App\Models\JawabanMahasiswa::where('percobaan_id', $percobaan->id)->exists();
        $isFirstStart    = ($section === 'listening') && !$sudahAdaJawaban;
        // Pastikan: pindah ke structure/reading = SELALU false (tidak splash)
        if ($section !== 'listening') $isFirstStart = false;

        // ── Audio global untuk listening ──
        $audioGlobal  = null;
        $tipeTes      = $percobaan->sesiTes?->tipe_tes ?? 'full';
        $startSeconds = []; // [soal_id => start_second] untuk sinkronisasi

        if ($section === 'listening') {
            // Cari audio paket yang terhubung ke soal-soal ini
            // Prioritas: audio tipe 'paket' (full) dari paket soal ini
            $soalIds = $soalList->pluck('soal.id')->filter()->toArray();
            if (!empty($soalIds)) {
                $sampleSoal = \App\Models\BankSoal::whereIn('id', $soalIds)
                    ->whereNotNull('audio_paket_id')->first();

                if ($sampleSoal?->audio_paket_id) {
                    $audioPaket = $sampleSoal->audioPaket;

                    if ($audioPaket && $audioPaket->tipe_upload === 'paket') {
                        // 1 audio full
                        $audioGlobal = $audioPaket->audio_url_full;
                    } elseif ($audioPaket && $audioPaket->tipe_upload === 'modul') {
                        // Audio per modul → buat playlist virtual
                        // Di user: tampil 1 demi 1 berdasarkan offset
                        $audioGlobal = $audioPaket->audio_url_full;
                    }
                }

                // Kumpulkan start_second per soal_id untuk sinkronisasi
                $startSeconds = \App\Models\BankSoal::whereIn('id', $soalIds)
                    ->whereNotNull('start_second')
                    ->pluck('start_second', 'id')
                    ->toArray();
            }
        }

        return view('user.tes.ujian', compact(
            'percobaan','soalList','currentSection',
            'sectionNum','durasiDetik','pendaftaran',
            'jawabanTersimpan','waktuBerakhir','isFirstStart',
            'audioGlobal','tipeTes','startSeconds'
        ));
    }

    // ─── AUTOSAVE JAWABAN ────────────────────────────────────────
    public function saveJawaban(Request $request)
    {
        $percobaanId = $request->percobaan_id;
        $soalId      = $request->soal_id;
        $jawaban     = $request->jawaban;

        $percobaan = PercobaanTes::where('id', $percobaanId)
            ->where('user_id', auth()->id())
            ->where('status', 'berlangsung')->first();

        if (!$percobaan)
            return response()->json(['ok'=>false,'msg'=>'Sesi tidak valid'], 422);

        // SERVER-SIDE: cek waktu
        if ($percobaan->waktuHabis()) {
            $this->selesaikan($percobaan);
            return response()->json(['ok'=>false,'msg'=>'Waktu habis','redirect'=>route('user.hasil.detail',$percobaan->id)], 403);
        }

        // Validasi soal milik sesi ini
        $soalValid = UrutanSoalSesi::where('percobaan_id', $percobaanId)
            ->where('soal_id', $soalId)->exists();
        if (!$soalValid)
            return response()->json(['ok'=>false,'msg'=>'Soal tidak valid'], 422);

        // Evaluasi di server (kunci tidak pernah ke front-end)
        $soal    = BankSoal::find($soalId);
        $isBenar = $soal && ($soal->jawaban_benar === $jawaban) ? 1 : 0;

        JawabanMahasiswa::updateOrCreate(
            ['percobaan_id' => $percobaanId, 'soal_id' => $soalId],
            [
                'jawaban_dipilih' => $jawaban,
                'is_benar'        => $isBenar,
                'status_soal'     => $request->status_soal ?? 'dijawab',
                'nomor_soal'      => $request->nomor_soal ?? 0,
                'waktu_dijawab'   => now(),
            ]
        );

        $percobaan->update(['last_autosave_at' => now()]);
        return response()->json(['ok' => true]);
    }

    // ─── AUTOSAVE PING ───────────────────────────────────────────
    public function autosave(Request $request)
    {
        $percobaan = PercobaanTes::where('id', $request->percobaan_id)
            ->where('user_id', auth()->id())
            ->where('status', 'berlangsung')->first();

        if (!$percobaan)
            return response()->json(['ok'=>false,'expired'=>true], 403);

        // SERVER-SIDE: cek waktu
        if ($percobaan->waktuHabis()) {
            $this->selesaikan($percobaan);
            return response()->json(['ok'=>false,'expired'=>true,'redirect'=>route('user.hasil.detail',$percobaan->id)], 403);
        }

        $percobaan->update(['last_autosave_at' => now()]);
        $sisaDetik = max(0, now()->diffInSeconds($percobaan->waktu_berakhir, false));
        return response()->json(['ok'=>true,'sisa_detik'=>$sisaDetik]);
    }

    // ─── SUBMIT SECTION ──────────────────────────────────────────
    public function submit(Request $request)
    {
        $userId    = auth()->id();
        $percobaan = PercobaanTes::where('id', $request->percobaan_id)
            ->where('user_id', $userId)
            ->where('status', 'berlangsung')->firstOrFail();

        $section = $request->section ?? 'reading';

        // Hitung dan simpan skor section ini
        $benar = JawabanMahasiswa::where('percobaan_id', $percobaan->id)
            ->whereHas('soal', fn($q) => $q->where('kategori', $section))
            ->where('is_benar', 1)->count();

        $skorSection = $this->hitungSkorSection($section, $benar);
        $percobaan->update(['skor_'.$section => $skorSection, 'last_autosave_at' => now()]);

        // FIX: force_finish = true → langsung selesaikan (pelanggaran / waktu habis)
        // Tidak perlu cek section berikutnya
        if ($request->boolean('force_finish')) {
            // Pastikan semua section punya skor (default 0 jika belum dikerjakan)
            $percobaan->refresh();
            if (!$percobaan->skor_listening) $percobaan->update(['skor_listening' => 0]);
            if (!$percobaan->skor_structure)  $percobaan->update(['skor_structure'  => 0]);
            if (!$percobaan->skor_reading)    $percobaan->update(['skor_reading'    => 0]);
            return $this->selesaikan($percobaan);
        }

        // Submit normal: cek section berikutnya
        $next = ['listening'=>'structure','structure'=>'reading','reading'=>null][$section] ?? null;

        if ($next) {
            // Simpan section terakhir untuk resume
            $percobaan->update(['last_section' => $next]);
            return redirect()->route('user.tes.ujian', [
                'percobaan_id' => $percobaan->id,
                'section'      => $next,
            ]);
        }

        return $this->selesaikan($percobaan);
    }

    // ─── SELESAIKAN TES ──────────────────────────────────────────
    private function selesaikan(PercobaanTes $percobaan)
    {
        $userId = $percobaan->user_id;

        // Hitung skor dari jawaban yang sudah tersimpan di DB
        $semuaJawaban = JawabanMahasiswa::where('percobaan_id', $percobaan->id)
            ->with('soal')->get();

        $benarL = $semuaJawaban->filter(fn($j) => $j->soal?->kategori === 'listening' && $j->is_benar)->count();
        $benarS = $semuaJawaban->filter(fn($j) => $j->soal?->kategori === 'structure' && $j->is_benar)->count();
        $benarR = $semuaJawaban->filter(fn($j) => $j->soal?->kategori === 'reading'   && $j->is_benar)->count();

        $sL    = $this->konversiListening($benarL);
        $sS    = $this->konversiStructure($benarS);
        $sR    = $this->konversiReading($benarR);
        $total = max(310, min(677, (int) round(($sL + $sS + $sR) * (10/3))));

        $benarTotal  = $benarL + $benarS + $benarR;
        $salah       = $semuaJawaban->where('is_benar', 0)->count();
        $durasiMenit = (int) round(\Carbon\Carbon::parse($percobaan->waktu_mulai)->diffInSeconds(now()) / 60);

        // ── SIMPAN HASIL DULU — redirect tidak boleh menunggu email ──
        $percobaan->fill([
            'waktu_selesai'        => now(),
            'skor_listening'       => $sL,
            'skor_structure'       => $sS,
            'skor_reading'         => $sR,
            'skor_total'           => $total,
            'jumlah_benar'         => $benarTotal,
            'jumlah_salah'         => $salah,
            'jumlah_tidak_dijawab' => max(0, $semuaJawaban->count() - $benarTotal - $salah),
            'status'               => 'selesai',
            'durasi_menit'         => $durasiMenit,
        ])->save();

        $percobaan->refresh();

        // ── GRAFIK PROGRESS ──
        try {
            GrafikProgress::create([
                'user_id'             => $userId,
                'sumber'              => 'tes_full',
                'sumber_id'           => $percobaan->id,
                'tanggal'             => today(),
                'skor_listening'      => $sL,
                'skor_structure'      => $sS,
                'skor_reading'        => $sR,
                'skor_toefl_estimasi' => $total,
            ]);
        } catch (\Exception $e) {
            \Log::warning('GrafikProgress: ' . $e->getMessage());
        }

        // ── UPDATE STATUS PENDAFTARAN → selesai ──────────────────────
        // Agar user bisa daftar sesi BARU setelah tes ini selesai
        PendaftaranTes::where('user_id', $userId)
            ->where('sesi_id', $percobaan->sesi_id)
            ->whereIn('status_pendaftaran', ['menunggu','dikonfirmasi'])
            ->update(['status_pendaftaran' => 'selesai']);

        // ── NOTIFIKASI IN-APP (cepat, tidak blocking) ──
        try {
            $user  = \App\Models\User::find($userId);
            $lulus = $total >= 500;
            app(NotificationService::class)->kirimNotifikasi(
                $user,
                $lulus ? '🎉 Selamat! Kamu Lulus TOEFL ITP' : '📊 Hasil Tes TOEFL Tersedia',
                $lulus
                    ? "Skor kamu {$total} — LULUS! Lihat detail dan cetak sertifikat."
                    : "Skor kamu {$total} — Belum lulus. Masih ada kesempatan, tetap semangat!",
                $lulus ? 'sukses' : 'warning',
                "/hasil/{$percobaan->id}",
                true
            );
        } catch (\Exception $e) {
            \Log::warning('Notifikasi: ' . $e->getMessage());
        }

        // ── REDIRECT KE HASIL SKOR — HARUS PERTAMA, tidak menunggu email ──
        // Email dikirim via register_shutdown_function agar tidak blocking
        $percobaanId = $percobaan->id;
        $userEmail   = $user->email ?? null;
        $percobaanCopy = $percobaan; // capture untuk closure

        register_shutdown_function(function() use ($userEmail, $percobaanCopy) {
            if (!$userEmail) return;
            try {
                \Mail::to($userEmail)->send(new \App\Mail\HasilTes($percobaanCopy));
            } catch (\Exception $e) {
                \Log::error('Email hasil tes: ' . $e->getMessage());
            }
        });

        return redirect()->route('user.hasil.detail', $percobaanId)
            ->with('success', "Tes selesai! Skor TOEFL ITP kamu: {$total}");
    }



    // ─── CATAT PELANGGARAN (ke tabel terstruktur) ────────────────
    public function catatPelanggaran(Request $request)
    {
        $percobaan = PercobaanTes::where('id', $request->percobaan_id)
            ->where('user_id', auth()->id())->first();

        if (!$percobaan) return response()->json(['ok'=>false], 204);
        if (in_array($percobaan->status, ['selesai','dibatalkan','expired']))
            return response()->json(['ok'=>false,'msg'=>'Tes sudah selesai'], 200);

        $jenis = $request->jenis ?? $request->tipe_aksi ?? 'lainnya';
        $jenisValid = ['tab_switch','copy_paste','klik_kanan','keluar_fullscreen','screenshot','lainnya'];
        if (!in_array($jenis, $jenisValid)) $jenis = 'lainnya';

        // Increment counter
        $percobaan->increment('jumlah_pelanggaran');
        $percobaan->refresh();

        // Simpan ke tabel pelanggaran terstruktur
        PelanggaranTes::create([
            'percobaan_id'   => $percobaan->id,
            'user_id'        => auth()->id(),
            'jenis'          => $jenis,
            'pelanggaran_ke' => $percobaan->jumlah_pelanggaran,
            'keterangan'     => $request->keterangan ?? null,
            'ip_address'     => $request->ip(),
            'waktu_pelanggaran' => now(),
        ]);

        // Jika >= 3 pelanggaran → force submit + flag curang
        if ($percobaan->jumlah_pelanggaran >= 3) {
            $percobaan->update([
                'status'        => 'berlangsung', // tetap berlangsung sampai selesaikan() dipanggil
                'status_curang' => true,
                'status_sanksi' => 'curang_pelanggaran',
            ]);

            $user = auth()->user();
            $user->update(['is_active' => 0]);

            Notifikasi::create([
                'user_id'      => $user->id,
                'judul'        => '⚠️ Tes Dihentikan - Kecurangan',
                'pesan'        => 'Tes kamu dihentikan karena 3x pelanggaran terdeteksi.',
                'tipe'         => 'danger',
                'is_important' => 1,
            ]);

            return response()->json([
                'pelanggaran_ke' => $percobaan->jumlah_pelanggaran,
                'force_submit'   => true,
                'redirect'       => route('user.tes.submit'),
            ]);
        }

        return response()->json(['pelanggaran_ke' => $percobaan->jumlah_pelanggaran]);
    }

    // ─── TES MINI ────────────────────────────────────────────────
    public function miniIndex()
    {
        // Tes Mini berdiri sendiri — tidak dari Bank Soal
        return view('user.tes.mini');
    }

    public function miniMulai(Request $request) { return redirect()->route('user.tes.mini'); }
    public function miniSubmit(Request $request){ return redirect()->route('user.tes.mini'); }

    // ─── TES SIMULASI ────────────────────────────────────────────
    public function simulasiIndex()
    {
        // Tes Simulasi berdiri sendiri — tidak dari Bank Soal
        return view('user.tes.simulasi');
    }

    public function simulasiMulai(Request $request)
    { return redirect()->route('user.tes.simulasi'); /* handled by JS */ //
        $userId  = auth()->id();
        $section = $request->section ?? 'listening';

        if ($section === 'listening') {
            session()->forget(['simulasi_percobaan_id','simulasi_jawaban','simulasi_soal_listening','simulasi_soal_structure','simulasi_soal_reading']);
            $percobaan = PercobaanTes::create([
                'user_id'     => $userId,
                'sesi_id'     => null,
                'tes_ke'      => PercobaanTes::where('user_id',$userId)->count() + 1,
                'waktu_mulai' => now(),
                'waktu_berakhir' => now()->addMinutes(80),
                'status'      => 'berlangsung',
                'ip_address'  => $request->ip(),
                'browser_info'=> substr($request->userAgent(),0,255),
            ]);
            session(['simulasi_percobaan_id' => $percobaan->id, 'simulasi_current_section' => $section]);
        }

        $percobaanId = session('simulasi_percobaan_id');
        $soalList    = BankSoal::where('kategori',$section)->where('is_aktif',1)->orderBy('id')->take(20)->get();
        $soalList->each(fn($s) => $s->audio_url_resolved = \App\Services\AudioService::resolveUrl($s->audio_url));
        // KEAMANAN
        $soalList->each(fn($s) => $s->makeHidden(['jawaban_benar','pembahasan']));

        session(["simulasi_soal_{$section}" => $soalList->pluck('id')->toArray(), 'simulasi_current_section' => $section]);

        $durasiMap   = ['listening'=>1200,'structure'=>900,'reading'=>1800];
        $sectionNum  = ['listening'=>1,'structure'=>2,'reading'=>3];
        return view('user.tes.simulasi_ujian', [
            'soalList'=>$soalList,'currentSection'=>$section,
            'sectionNum'=>$sectionNum,'durasi'=>$durasiMap[$section]??1200,'percobaanId'=>$percobaanId,
        ]);
    }

    public function simulasiUjian(Request $request)
    {
        $section     = $request->section ?? session('simulasi_current_section','listening');
        $percobaanId = session('simulasi_percobaan_id');
        if (!$percobaanId)
            return redirect()->route('user.tes.simulasi')->with('error','Sesi simulasi tidak ditemukan. Mulai ulang.');

        $soalList = BankSoal::where('kategori',$section)->where('is_aktif',1)->orderBy('id')->take(20)->get();
        $soalList->each(fn($s) => $s->audio_url_resolved = \App\Services\AudioService::resolveUrl($s->audio_url));
        $soalList->each(fn($s) => $s->makeHidden(['jawaban_benar','pembahasan']));
        $sectionNum = ['listening'=>1,'structure'=>2,'reading'=>3];
        $durasiMap  = ['listening'=>1200,'structure'=>900,'reading'=>1800];
        return view('user.tes.simulasi_ujian', compact('soalList') + [
            'currentSection'=>$section,'sectionNum'=>$sectionNum,
            'durasi'=>$durasiMap[$section]??1200,'percobaanId'=>$percobaanId,
        ]);
    }

    public function simulasiSubmit(Request $request)
    {
        $section     = $request->section;
        $jawaban     = $request->input('jawaban', []);
        $percobaanId = $request->percobaan_id ?? session('simulasi_percobaan_id');
        $allJawaban  = session('simulasi_jawaban', []);
        $allJawaban[$section] = $jawaban;
        session(['simulasi_jawaban' => $allJawaban]);

        $next = ['listening'=>'structure','structure'=>'reading','reading'=>null][$section] ?? null;
        if ($next)
            return redirect()->route('user.tes.simulasi.ujian', ['section'=>$next]);

        return $this->simulasiSelesai($percobaanId);
    }

    private function simulasiSelesai($percobaanId)
    {
        $userId     = auth()->id();
        $allJawaban = session('simulasi_jawaban', []);
        $review     = [];
        $benarL=$benarS=$benarR = 0;

        foreach (['listening','structure','reading'] as $kat) {
            $soalIds = session("simulasi_soal_{$kat}", []);
            foreach ($soalIds as $soalId) {
                $soal    = BankSoal::find($soalId);
                if (!$soal) continue;
                $jwb     = ($allJawaban[$kat][$soalId]) ?? null;
                $isBenar = $jwb && ($soal->jawaban_benar === $jwb);
                if ($isBenar) { if($kat==='listening')$benarL++; elseif($kat==='structure')$benarS++; else $benarR++; }
                $review[] = ['soal'=>$soal,'jawaban_user'=>$jwb,'is_benar'=>$isBenar];
            }
        }

        $sL=$this->konversiListening($benarL);
        $sS=$this->konversiStructure($benarS);
        $sR=$this->konversiReading($benarR);
        $total = max(310,min(677,(int)round(($sL+$sS+$sR)*(10/3))));

        if ($percobaanId) {
            PercobaanTes::where('id',$percobaanId)->update([
                'waktu_selesai'=>now(),'skor_listening'=>$sL,'skor_structure'=>$sS,
                'skor_reading'=>$sR,'skor_total'=>$total,'status'=>'selesai',
            ]);
            GrafikProgress::create([
                'user_id'=>$userId,'sumber'=>'simulasi','sumber_id'=>$percobaanId,
                'tanggal'=>today(),'skor_listening'=>$sL,'skor_structure'=>$sS,
                'skor_reading'=>$sR,'skor_toefl_estimasi'=>$total,
            ]);
        }

        session()->forget(['simulasi_percobaan_id','simulasi_jawaban','simulasi_soal_listening','simulasi_soal_structure','simulasi_soal_reading','simulasi_current_section']);
        return view('user.tes.simulasi_hasil', compact('review','sL','sS','sR','total'));
    }

    // ─── HELPERS ─────────────────────────────────────────────────
    private function initJawaban(PercobaanTes $p, string $section): void
    {
        // Sudah ada urutan soal dari simpanUrutanSoal, tidak perlu duplikat
    }

    private function hitungSkorSection(string $section, int $benar): int
    {
        return match($section) {
            'listening' => $this->konversiListening($benar),
            'structure' => $this->konversiStructure($benar),
            'reading'   => $this->konversiReading($benar),
            default     => 0,
        };
    }

    private function konversiListening(int $benar): int
    {
        $tabel = [0=>25,1=>25,2=>26,3=>27,4=>28,5=>29,6=>30,7=>31,8=>32,9=>33,10=>34,
            11=>36,12=>38,13=>40,14=>42,15=>44,16=>46,17=>48,18=>50,19=>52,20=>54,
            21=>56,22=>58,23=>60,24=>62,25=>64,26=>65,27=>66,28=>67,29=>68,30=>68,
            31=>68,32=>68,33=>68,34=>68,35=>68,36=>68,37=>68,38=>68,39=>68,40=>68,
            41=>68,42=>68,43=>68,44=>68,45=>68,46=>68,47=>68,48=>68,49=>68,50=>68];
        return $tabel[$benar] ?? 68;
    }

    private function konversiStructure(int $benar): int
    {
        $tabel = [0=>25,1=>26,2=>28,3=>30,4=>32,5=>34,6=>36,7=>38,8=>40,9=>41,
            10=>42,11=>43,12=>44,13=>46,14=>48,15=>50,16=>52,17=>54,18=>56,19=>58,
            20=>60,21=>62,22=>64,23=>65,24=>66,25=>67,26=>68,27=>68,28=>68,29=>68,
            30=>68,31=>68,32=>68,33=>68,34=>68,35=>68,36=>68,37=>68,38=>68,39=>68,40=>68];
        return $tabel[$benar] ?? 68;
    }

    private function konversiReading(int $benar): int
    {
        $tabel = [0=>25,1=>26,2=>27,3=>28,4=>29,5=>30,6=>31,7=>32,8=>33,9=>34,
            10=>35,11=>36,12=>37,13=>38,14=>39,15=>40,16=>42,17=>44,18=>46,19=>48,
            20=>50,21=>52,22=>54,23=>56,24=>58,25=>60,26=>61,27=>62,28=>63,29=>64,
            30=>65,31=>66,32=>67,33=>68,34=>68,35=>68,36=>68,37=>68,38=>68,39=>68,
            40=>68,41=>68,42=>68,43=>68,44=>68,45=>68,46=>68,47=>68,48=>68,49=>68,50=>68];
        return $tabel[$benar] ?? 68;
    }

    private function hitungSkorMini(array $review, string $kategori): float
    {
        $benar = collect($review)->filter(fn($r) => $r['soal']->kategori===$kategori && $r['is_benar'])->count();
        $total = collect($review)->filter(fn($r) => $r['soal']->kategori===$kategori)->count();
        return $total > 0 ? round($benar/$total*100, 1) : 0;
    }
}
