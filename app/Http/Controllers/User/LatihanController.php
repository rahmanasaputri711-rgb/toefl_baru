<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BankSoal;
use Illuminate\Http\Request;

class LatihanController extends Controller
{
    /**
     * Halaman pilih kategori latihan — tampil card kategori
     */
    public function index()
    {
        $stats = [
            'reading'   => BankSoal::where('kategori','reading')  ->where('is_aktif',1)->count(),
            'listening' => BankSoal::where('kategori','listening')->where('is_aktif',1)->count(),
            'structure' => BankSoal::where('kategori','structure')->where('is_aktif',1)->count(),
        ];
        return view('user.latihan.index', compact('stats'));
    }

    /**
     * Halaman kerjakan soal per kategori — satu soal per tampilan (card)
     */
    public function kerjakan(Request $request, string $kategori)
    {
        abort_if(!in_array($kategori, ['reading','listening','structure']), 404);

        $soalList = BankSoal::where('kategori', $kategori)
            ->where('is_aktif', 1)
            ->orderBy('id')
            ->get();

        if ($soalList->isEmpty()) {
            return back()->with('error', 'Belum ada soal untuk kategori '.ucfirst($kategori).'.');
        }

        $nomorSoal = max(1, min((int)$request->get('no', 1), $soalList->count()));
        $soal      = $soalList[$nomorSoal - 1];
        $total     = $soalList->count();

        // Jawaban tersimpan di session untuk navigasi maju mundur
        $sessionKey = 'latihan_'.$kategori;
        $jawaban    = session($sessionKey, []);

        return view('user.latihan.kerjakan', compact(
            'soal','kategori','nomorSoal','total','jawaban','sessionKey'
        ));
    }

    /**
     * Simpan jawaban satu soal, lanjut ke soal berikutnya
     */
    public function simpanJawaban(Request $request, string $kategori)
    {
        abort_if(!in_array($kategori, ['reading','listening','structure']), 404);

        $sessionKey = 'latihan_'.$kategori;
        $jawaban    = session($sessionKey, []);

        if ($request->filled('jawaban')) {
            $jawaban[$request->soal_id] = $request->jawaban;
            session([$sessionKey => $jawaban]);
        }

        $total     = BankSoal::where('kategori',$kategori)->where('is_aktif',1)->count();
        $nomorSoal = (int)$request->nomor_soal;

        // Jika klik "selesai" atau soal terakhir
        if ($request->has('selesai') || $nomorSoal >= $total) {
            return redirect()->route('user.latihan.hasil', $kategori);
        }

        return redirect()->route('user.latihan.kerjakan', [
            'kategori' => $kategori,
            'no'       => $nomorSoal + 1,
        ]);
    }

    /**
     * Halaman hasil & pembahasan setelah semua soal dikerjakan
     */
    public function hasil(string $kategori)
    {
        abort_if(!in_array($kategori, ['reading','listening','structure']), 404);

        $sessionKey = 'latihan_'.$kategori;
        $jawaban    = session($sessionKey, []);

        $soalList   = BankSoal::where('kategori', $kategori)
            ->where('is_aktif', 1)->orderBy('id')->get();

        $review      = [];
        $jumlahBenar = 0;

        foreach ($soalList as $s) {
            $jwb     = $jawaban[$s->id] ?? null;
            $isBenar = $jwb && ($s->jawaban_benar === $jwb);
            if ($isBenar) $jumlahBenar++;
            $review[] = [
                'soal'         => $s,
                'jawaban_user' => $jwb,
                'jawaban_benar'=> $s->jawaban_benar,
                'is_benar'     => $isBenar,
            ];
        }

        $jumlahSoal = $soalList->count();
        $persentase = $jumlahSoal > 0 ? round(($jumlahBenar / $jumlahSoal) * 100) : 0;

        // Hapus session setelah selesai
        session()->forget($sessionKey);

        return view('user.latihan.hasil', compact(
            'review','jumlahBenar','jumlahSoal','persentase','kategori'
        ));
    }

    /**
     * Reset latihan (kembali ke awal kategori)
     */
    public function reset(string $kategori)
    {
        session()->forget('latihan_'.$kategori);
        return redirect()->route('user.latihan.kerjakan', ['kategori' => $kategori, 'no' => 1]);
    }
}
