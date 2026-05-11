<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{PaketSoal, GrupSoal, ModulSoal, Passage, BankSoal};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReadingBuilderController extends Controller
{
    // ══════════════════════════════════════════════════════════════
    // PAKET — halaman utama builder
    // ══════════════════════════════════════════════════════════════

    /** Daftar paket soal */
    public function index()
    {
        $paketList = PaketSoal::with(['detail'])->orderByDesc('created_at')->get();
        return redirect()->route('admin.paket-builder.index');
    }

    /** Redirect ke paket-builder (sistem baru) */
    public function showPaket($paketId)
    {
        return redirect()->route('admin.paket-builder.paket', $paketId);
    }

    // ══════════════════════════════════════════════════════════════
    // MODUL — buat modul baru dalam paket
    // ══════════════════════════════════════════════════════════════

    /** Form buat modul baru */
    public function createModul($paketId)
    {
        $paket = PaketSoal::findOrFail($paketId);

        // Hitung nomor soal yang sudah dipakai
        $soalTerpakai = ModulSoal::where('paket_id', $paketId)
            ->selectRaw('nomor_soal_mulai, nomor_soal_selesai')
            ->get();

        $nomorBerikut = ModulSoal::where('paket_id', $paketId)->max('nomor_soal_selesai') + 1;

        return view('admin.reading-builder.create-modul', compact(
            'paket', 'soalTerpakai', 'nomorBerikut'
        ));
    }

    /** Simpan modul baru */
    public function storeModul(Request $request, $paketId)
    {
        $paket = PaketSoal::findOrFail($paketId);

        $request->validate([
            'tipe_modul'         => 'required|in:passage,missing_letters,image_email',
            'nomor_soal_mulai'   => 'required|integer|min:1',
            'nomor_soal_selesai' => 'required|integer|gte:nomor_soal_mulai',
            'judul'              => 'nullable|string|max:200',
        ]);

        // Cek overlap nomor soal
        $overlap = ModulSoal::where('paket_id', $paketId)
            ->where(function($q) use ($request) {
                $q->whereBetween('nomor_soal_mulai',  [$request->nomor_soal_mulai, $request->nomor_soal_selesai])
                  ->orWhereBetween('nomor_soal_selesai', [$request->nomor_soal_mulai, $request->nomor_soal_selesai]);
            })->exists();

        if ($overlap)
            return back()->withInput()
                ->with('error', "Rentang soal {$request->nomor_soal_mulai}–{$request->nomor_soal_selesai} sudah digunakan modul lain.");

        // Buat atau ambil grup reading untuk paket ini
        $grup = GrupSoal::firstOrCreate(
            ['created_by' => auth()->id(), 'kategori' => 'reading'],
            ['judul' => 'Group Reading — ' . $paket->nama, 'is_aktif' => true]
        );

        $modul = ModulSoal::create([
            'paket_id'           => $paketId,
            'grup_id'            => $grup->id,
            'created_by'         => auth()->id(),
            'tipe_modul'         => $request->tipe_modul,
            'judul'              => $request->judul,
            'nomor_soal_mulai'   => $request->nomor_soal_mulai,
            'nomor_soal_selesai' => $request->nomor_soal_selesai,
            'urutan'             => $request->nomor_soal_mulai,
        ]);

        // Redirect ke form input sesuai tipe modul
        return redirect()->route('admin.reading-builder.modul.input', $modul->id)
            ->with('success', "Modul berhasil dibuat! Sekarang input konten.");
    }

    // ══════════════════════════════════════════════════════════════
    // INPUT KONTEN MODUL
    // ══════════════════════════════════════════════════════════════

    /** Halaman input konten — masing-masing tipe modul punya form berbeda */
    public function inputModul($modulId)
    {
        $modul = ModulSoal::with(['paket','soal','passages.soal'])->findOrFail($modulId);
        $paket = $modul->paket;

        return match($modul->tipe_modul) {
            'passage'         => view('admin.reading-builder.input-passage',        compact('modul','paket')),
            'missing_letters' => view('admin.reading-builder.input-missing-letters', compact('modul','paket')),
            'image_email'     => view('admin.reading-builder.input-image-email',     compact('modul','paket')),
            default           => abort(404),
        };
    }

    // ══════════════════════════════════════════════════════════════
    // PASSAGE MODULE — simpan passage + soal
    // ══════════════════════════════════════════════════════════════

    /** Simpan passage baru dalam modul */
    public function storePassage(Request $request, $modulId)
    {
        $modul = ModulSoal::findOrFail($modulId);
        $request->validate([
            'judul' => 'required|string|max:255',
            'teks'  => 'required|string|min:50',
        ]);

        $passage = Passage::create([
            'modul_id'   => $modul->id,
            'paket_id'   => $modul->paket_id,
            'created_by' => auth()->id(),
            'judul'      => $request->judul,
            'teks'       => $request->teks,
            'tipe_paket' => 'full',
            'is_aktif'   => true,
        ]);

        return response()->json(['ok' => true, 'passage_id' => $passage->id, 'msg' => 'Passage disimpan.']);
    }

    /** Simpan soal passage */
    public function storeSoalPassage(Request $request, $modulId)
    {
        $modul = ModulSoal::findOrFail($modulId);
        $request->validate([
            'passage_id'      => 'required|exists:passages,id',
            'nomor_dalam_paket' => 'required|integer|min:1',
            'tipe_soal'       => 'required|in:multiple_choice,vocabulary,insert_sentence,click_sentence',
            'pertanyaan'      => 'required|string',
            'pilihan_a'       => 'required|string',
            'pilihan_b'       => 'required|string',
            'pilihan_c'       => 'required|string',
            'pilihan_d'       => 'required|string',
            'jawaban_benar'   => 'required|in:a,b,c,d',
        ]);

        $soal = BankSoal::create([
            'modul_id'           => $modul->id,
            'paket_id'           => $modul->paket_id,
            'passage_id'         => $request->passage_id,
            'nomor_dalam_paket'  => $request->nomor_dalam_paket,
            'kategori'           => 'reading',
            'tipe_paket'         => 'full',
            'tipe_soal'          => $request->tipe_soal,
            'pertanyaan'         => $request->pertanyaan,
            'highlight_kata'     => $request->highlight_kata,
            'highlight_paragraf' => $request->highlight_paragraf,
            'pilihan_a'          => $request->pilihan_a,
            'pilihan_b'          => $request->pilihan_b,
            'pilihan_c'          => $request->pilihan_c,
            'pilihan_d'          => $request->pilihan_d,
            'jawaban_benar'      => $request->jawaban_benar,
            'pembahasan'         => $request->pembahasan,
            'tingkat_kesulitan'  => $request->tingkat_kesulitan ?? 'medium',
            'is_aktif'           => true,
            'created_by'         => auth()->id(),
        ]);

        return response()->json(['ok' => true, 'soal_id' => $soal->id,
            'msg' => "Soal No.{$request->nomor_dalam_paket} tersimpan."]);
    }

    // ══════════════════════════════════════════════════════════════
    // MISSING LETTERS MODULE
    // ══════════════════════════════════════════════════════════════

    /** Simpan modul missing letters — 1 teks = banyak blank = banyak soal */
    public function storeMissingLetters(Request $request, $modulId)
    {
        $modul = ModulSoal::findOrFail($modulId);
        $request->validate(['fill_text' => 'required|string|min:20']);

        $fillText = $request->fill_text;

        // Ekstrak semua jawaban dari [...]
        preg_match_all('/\[([^\]]+)\]/', $fillText, $matches);
        $answers = $matches[1];
        $jumlah  = count($answers);

        if ($jumlah === 0)
            return response()->json(['ok' => false, 'msg' => 'Tidak ada blank [...] ditemukan.']);

        $expected = $modul->nomor_soal_selesai - $modul->nomor_soal_mulai + 1;
        if ($jumlah !== $expected)
            return response()->json(['ok' => false,
                'msg' => "Jumlah blank ({$jumlah}) tidak sesuai rentang soal ({$expected}). Sesuaikan teks atau ubah rentang modul."]);

        // Simpan sebagai 1 soal tipe fill_missing_letters
        // (1 soal = 1 teks penuh dengan semua blank)
        DB::transaction(function() use ($modul, $fillText, $answers, $request) {
            // Hapus soal lama jika ada (replace)
            BankSoal::where('modul_id', $modul->id)->delete();

            BankSoal::create([
                'modul_id'           => $modul->id,
                'paket_id'           => $modul->paket_id,
                'nomor_dalam_paket'  => $modul->nomor_soal_mulai,
                'urutan_soal'        => $modul->nomor_soal_mulai,
                'kategori'           => 'reading',
                'tipe_paket'         => 'full',
                'tipe_soal'          => 'fill_missing_letters',
                'pertanyaan'         => 'Fill in the missing letters in the paragraph.',
                'fill_text'          => $fillText,
                'jawaban_benar'      => implode('|', $answers), // simpan semua jawaban
                'pilihan_a'          => '-',
                'pilihan_b'          => '-',
                'pilihan_c'          => '-',
                'pilihan_d'          => '-',
                'tingkat_kesulitan'  => $request->tingkat_kesulitan ?? 'medium',
                'pembahasan'         => $request->pembahasan,
                'is_aktif'           => true,
                'created_by'         => auth()->id(),
            ]);

            $modul->update(['is_selesai' => true]);
        });

        return response()->json(['ok' => true,
            'msg' => "Berhasil! {$jumlah} blank disimpan sebagai 1 modul Missing Letters.",
            'jumlah_blank' => $jumlah]);
    }

    // ══════════════════════════════════════════════════════════════
    // IMAGE / EMAIL MODULE
    // ══════════════════════════════════════════════════════════════

    /** Simpan gambar/email + soal */
    public function storeImageEmail(Request $request, $modulId)
    {
        $modul = ModulSoal::findOrFail($modulId);
        $request->validate([
            'image'         => 'nullable|image|max:5120',
            'email_meta'    => 'nullable|string',
            'pertanyaan'    => 'required|string',
            'nomor_dalam_paket' => 'required|integer',
            'pilihan_a'     => 'required|string',
            'pilihan_b'     => 'required|string',
            'pilihan_c'     => 'required|string',
            'pilihan_d'     => 'required|string',
            'jawaban_benar' => 'required|in:a,b,c,d',
        ]);

        $imagePath = null;
        if ($request->hasFile('image'))
            $imagePath = $request->file('image')->store('soal/images', 'public');

        $soal = BankSoal::create([
            'modul_id'          => $modul->id,
            'paket_id'          => $modul->paket_id,
            'nomor_dalam_paket' => $request->nomor_dalam_paket,
            'kategori'          => 'reading',
            'tipe_paket'        => 'full',
            'tipe_soal'         => $imagePath ? 'academic_passage' : 'email_reading',
            'image_url'         => $imagePath,
            'email_meta'        => $request->email_meta,
            'pertanyaan'        => $request->pertanyaan,
            'pilihan_a'         => $request->pilihan_a,
            'pilihan_b'         => $request->pilihan_b,
            'pilihan_c'         => $request->pilihan_c,
            'pilihan_d'         => $request->pilihan_d,
            'jawaban_benar'     => $request->jawaban_benar,
            'pembahasan'        => $request->pembahasan,
            'tingkat_kesulitan' => $request->tingkat_kesulitan ?? 'medium',
            'is_aktif'          => true,
            'created_by'        => auth()->id(),
        ]);

        return response()->json(['ok' => true, 'soal_id' => $soal->id,
            'msg' => "Soal No.{$request->nomor_dalam_paket} tersimpan."]);
    }

    /** Tandai paket selesai */
    public function selesaikanPaket($paketId)
    {
        $paket = PaketSoal::findOrFail($paketId);
        $paket->validate(); // hitung jumlah soal per kategori
        $paket->update(['status' => 'valid']);
        return back()->with('success', 'Paket soal berhasil diselesaikan!');
    }

    /** Hapus modul + semua soalnya */
    public function destroyModul($modulId)
    {
        $modul = ModulSoal::findOrFail($modulId);
        $paketId = $modul->paket_id;
        DB::transaction(function() use ($modul) {
            BankSoal::where('modul_id', $modul->id)->delete();
            Passage::where('modul_id', $modul->id)->delete();
            $modul->delete();
        });
        return redirect()->route('admin.reading-builder.paket', $paketId)
            ->with('success', 'Modul berhasil dihapus.');
    }
}