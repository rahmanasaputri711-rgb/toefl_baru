<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SesiTes;
use App\Models\BankSoal;
use App\Models\SesiTesSoal;
use App\Models\PendaftaranTes;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use App\Services\FisherYatesService;

class SesiTesController extends Controller
{
    // ── Konfigurasi standar per tipe tes (server-side mirror dari JS) ──────────
    private const TIPE_PRESET = [
        'full' => [
            'durasi_menit'          => 115,
            'jumlah_soal_listening' => 50,
            'jumlah_soal_structure' => 40,
            'jumlah_soal_reading'   => 50,
            'tampilkan_hasil'       => 1,
            'tampilkan_pembahasan'  => 0,  // Full: pembahasan tidak ditampilkan
        ],
        'simulasi' => [
            'durasi_menit'          => 65,
            'jumlah_soal_listening' => 30,
            'jumlah_soal_structure' => 20,
            'jumlah_soal_reading'   => 30,
            'tampilkan_hasil'       => 1,
            'tampilkan_pembahasan'  => 1,
        ],
        'mini' => [
            'durasi_menit'          => 30,
            'jumlah_soal_listening' => 15,
            'jumlah_soal_structure' => 10,
            'jumlah_soal_reading'   => 15,
            'tampilkan_hasil'       => 1,
            'tampilkan_pembahasan'  => 1,
        ],
    ];

    public function index()
    {
        $sesi = SesiTes::latest()->paginate(10);
        return view('admin.sesi.index', compact('sesi'));
    }

    public function create()
    {
        $totalReading   = BankSoal::where('kategori','reading')
            ->where('is_aktif',1)->where('untuk_tes_full',1)->count();
        $totalListening = BankSoal::where('kategori','listening')
            ->where('is_aktif',1)->where('untuk_tes_full',1)->count();
        $totalStructure = BankSoal::where('kategori','structure')
            ->where('is_aktif',1)->where('untuk_tes_full',1)->count();

        return view('admin.sesi.create', compact(
            'totalReading','totalListening','totalStructure'
        ));
    }

    public function store(Request $request)
    {
        // 1. Validasi field dasar
        $request->validate([
            'judul'       => 'required|string|max:255',
            'tipe_tes'    => 'required|in:full,simulasi,mini',
            'waktu_mulai' => 'required|date',
            'kuota_peserta' => 'nullable|integer|min:1|max:500',
        ]);

        $tipe   = $request->tipe_tes;
        $preset = self::TIPE_PRESET[$tipe];

        // 2. Override semua nilai dari preset (tidak percaya input user untuk field konfigurasi)
        $durasi    = $preset['durasi_menit'];
        $waktuMulai = new \Carbon\Carbon($request->waktu_mulai);
        $waktuSelesai = $waktuMulai->copy()->addMinutes($durasi);

        // 3. Kuota: ambil dari input user (boleh ubah), default dari preset jika kosong
        $kuota = $request->filled('kuota_peserta')
            ? (int) $request->kuota_peserta
            : 50;

        // 4. Simpan sesi
        $sesi = SesiTes::create([
            'admin_id'               => auth()->id(),
            'judul'                  => $request->judul,
            'deskripsi'              => $request->deskripsi,
            'tipe_tes'               => $tipe,
            'durasi_menit'           => $durasi,
            'jumlah_soal_listening'  => $preset['jumlah_soal_listening'],
            'jumlah_soal_structure'  => $preset['jumlah_soal_structure'],
            'jumlah_soal_reading'    => $preset['jumlah_soal_reading'],
            'kuota_peserta'          => $kuota,
            'waktu_mulai'            => $waktuMulai,
            'waktu_selesai'          => $waktuSelesai,
            'tampilkan_hasil'        => $preset['tampilkan_hasil'],
            'tampilkan_pembahasan'   => $preset['tampilkan_pembahasan'],
            'is_aktif'               => 0,
        ]);

        // 5. Jalankan Fisher-Yates hanya untuk Tes Full
        if ($tipe === 'full') {
            $this->assignSoalFisherYates($sesi);
        }

        return redirect()->route('admin.sesi.index')
            ->with('success', "Sesi \"{$sesi->judul}\" berhasil dibuat.");
    }

    public function edit($id)
    {
        $sesi = SesiTes::findOrFail($id);
        return view('admin.sesi.edit', compact('sesi'));
    }

    public function update(Request $request, $id)
    {
        $sesi = SesiTes::findOrFail($id);

        $request->validate([
            'judul'         => 'required|string|max:255',
            'waktu_mulai'   => 'required|date',
            'kuota_peserta' => 'nullable|integer|min:1|max:500',
        ]);

        // Durasi & jumlah soal tetap dari preset — tidak bisa diubah lewat edit
        $preset       = self::TIPE_PRESET[$sesi->tipe_tes];
        $waktuMulai   = new \Carbon\Carbon($request->waktu_mulai);
        $waktuSelesai = $waktuMulai->copy()->addMinutes($preset['durasi_menit']);

        $sesi->update([
            'judul'         => $request->judul,
            'deskripsi'     => $request->deskripsi,
            'kuota_peserta' => $request->filled('kuota_peserta')
                                ? (int) $request->kuota_peserta
                                : $sesi->kuota_peserta,
            'waktu_mulai'   => $waktuMulai,
            'waktu_selesai' => $waktuSelesai,
        ]);

        // Notifikasi ke pendaftar yang sudah dikonfirmasi
        $pendaftaran = PendaftaranTes::where('sesi_id', $id)
            ->where('status_pendaftaran','dikonfirmasi')->get();
        foreach ($pendaftaran as $p) {
            Notifikasi::create([
                'user_id' => $p->user_id,
                'judul'   => 'Jadwal Tes Diperbarui',
                'pesan'   => "Jadwal sesi \"{$sesi->judul}\" telah diperbarui. Silakan cek detail terbaru.",
                'tipe'    => 'jadwal',
            ]);
        }

        return redirect()->route('admin.sesi.index')
            ->with('success', 'Sesi diperbarui. Notifikasi dikirim ke peserta.');
    }

    public function toggleAktif($id)
    {
        $sesi = SesiTes::findOrFail($id);
        $sesi->update(['is_aktif' => !$sesi->is_aktif]);
        $status = $sesi->fresh()->is_aktif ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Sesi \"{$sesi->judul}\" berhasil {$status}.");
    }

    public function destroy($id)
    {
        $sesi = SesiTes::findOrFail($id);
        $sesi->delete();
        return redirect()->route('admin.sesi.index')
            ->with('success', 'Sesi berhasil dihapus.');
    }

    /**
     * Fisher-Yates: pilih & acak soal per bagian, simpan ke sesi_tes_soal.
     * Listening: urutan tetap (menjaga alur audio).
     * Structure & Reading: diacak Fisher-Yates.
     */
    private function assignSoalFisherYates(SesiTes $sesi): void
    {
        $categories = [
            'listening' => $sesi->jumlah_soal_listening,
            'structure' => $sesi->jumlah_soal_structure,
            'reading'   => $sesi->jumlah_soal_reading,
        ];

        foreach ($categories as $kategori => $jumlah) {
            if ($jumlah <= 0) continue;

            $allIds = BankSoal::where('kategori', $kategori)
                ->where('is_aktif', 1)
                ->where('untuk_tes_full', 1)
                ->pluck('id')
                ->toArray();

            // Listening: tidak diacak (urutan tetap untuk alur audio)
            // Structure & Reading: diacak Fisher-Yates
            $shuffled = FisherYatesService::shuffleSoal($allIds, $kategori, false);
            $selected = array_slice($shuffled, 0, min($jumlah, count($shuffled)));

            foreach ($selected as $urutan => $soalId) {
                SesiTesSoal::create([
                    'sesi_id'     => $sesi->id,
                    'soal_id'     => $soalId,
                    'bagian'      => $kategori,
                    'urutan_acak' => $urutan + 1,
                ]);
            }
        }
    }
    /** Detail sesi + daftar peserta untuk absensi */
    public function show($id)
    {
        $sesi   = \App\Models\SesiTes::findOrFail($id);
        $peserta = \App\Models\PendaftaranTes::with('user')
            ->where('sesi_id', $id)
            ->whereIn('status_pendaftaran', ['dikonfirmasi','menunggu'])
            ->paginate(30);

        $kuota         = $sesi->kuota_peserta;
        $statHadir     = \App\Models\PendaftaranTes::where('sesi_id',$id)->where('is_hadir',true)->count();
        $statAbsen     = \App\Models\PendaftaranTes::where('sesi_id',$id)->where('is_hadir',false)->count();
        $statBelumAbsen= \App\Models\PendaftaranTes::where('sesi_id',$id)
            ->where('status_pendaftaran','dikonfirmasi')->whereNull('is_hadir')->count();

        return view('admin.sesi.show', compact(
            'sesi','peserta','kuota','statHadir','statAbsen','statBelumAbsen'
        ));
    }

    /** Tandai peserta HADIR */
    public function tandaiHadir($pendaftaranId)
    {
        \App\Models\PendaftaranTes::where('id', $pendaftaranId)
            ->update(['is_hadir' => true, 'ditandai_absen_at' => now()]);
        return back()->with('success', 'Peserta ditandai hadir.');
    }

    /** Reset status kehadiran */
    public function resetHadir($pendaftaranId)
    {
        \App\Models\PendaftaranTes::where('id', $pendaftaranId)
            ->update(['is_hadir' => null, 'ditandai_absen_at' => null]);
        return back()->with('success', 'Status kehadiran direset.');
    }

}