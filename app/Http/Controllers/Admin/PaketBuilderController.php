<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{PaketSoal, GrupSoal, ModulSoal, Passage, BankSoal};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaketBuilderController extends Controller
{
    // ══ PAKET ═════════════════════════════════════════════════════
    public function index() {
        $paketList = PaketSoal::withCount(['bankSoal'])->orderByDesc('created_at')->get();
        return view('admin.paket-builder.index', compact('paketList'));
    }

    public function createPaket() {
        return view('admin.paket-builder.create-paket');
    }

    public function storePaket(Request $request) {
        $request->validate(['nama'=>'required|string|max:200']);
        $paket = PaketSoal::create([
            'created_by' => auth()->id(),
            'nama'       => trim($request->nama),
            'deskripsi'  => $request->deskripsi,
            'status'     => 'draft',
            'is_aktif'   => false,
        ]);
        return redirect()->route('admin.paket-builder.paket', $paket->id)
            ->with('success','Paket berhasil dibuat! Tambahkan group soal.');
    }

    // ══ PAKET DETAIL ══════════════════════════════════════════════
    public function showPaket($paketId) {
        $paket = PaketSoal::findOrFail($paketId);

        $grupList = GrupSoal::with(['modul.soal','modul.passages'])
            ->where('paket_id', $paketId)
            ->orderBy('kategori')->get();

        // Semua soal terurut nomor
        $soalGlobal = BankSoal::with(['passage','modul'])
            ->where('paket_id', $paketId)
            ->orderBy('nomor_dalam_paket')->get();

        return view('admin.paket-builder.paket',
            compact('paket','grupList','soalGlobal'));
    }

    // ══ GRUP ══════════════════════════════════════════════════════
    public function storeGrup(Request $request, $paketId) {
        $request->validate(['kategori'=>'required|in:reading,listening,structure']);
        $paket = PaketSoal::findOrFail($paketId);

        // Cegah duplikat grup kategori sama dalam 1 paket
        if (GrupSoal::where('paket_id',$paketId)->where('kategori',$request->kategori)->exists())
            return back()->with('error','Grup '.ucfirst($request->kategori).' sudah ada di paket ini.');

        GrupSoal::create([
            'paket_id'   => $paketId,
            'created_by' => auth()->id(),
            'kategori'   => $request->kategori,
            'judul'      => 'Group '.ucfirst($request->kategori).' — '.$paket->nama,
            'is_aktif'   => true,
        ]);
        return back()->with('success','Group '.ucfirst($request->kategori).' berhasil ditambahkan.');
    }

    // ══ MODUL ═════════════════════════════════════════════════════
    public function createModul($paketId, $grupId) {
        $paket = PaketSoal::findOrFail($paketId);
        $grup  = GrupSoal::where('paket_id',$paketId)->findOrFail($grupId);

        $terpakai = ModulSoal::where('paket_id',$paketId)
            ->where('grup_id',$grupId)->get();

        $nomorBerikut = $terpakai->max('nomor_soal_selesai') + 1 ?: 1;

        return view('admin.paket-builder.create-modul',
            compact('paket','grup','terpakai','nomorBerikut'));
    }

    public function storeModul(Request $request, $paketId, $grupId) {
        $paket = PaketSoal::findOrFail($paketId);
        $grup  = GrupSoal::findOrFail($grupId);

        $request->validate([
            'tipe_modul'         => 'required|in:passage,missing_letters,image_email,conversation,lecture,discussion,short_talk,best_response,arrange_sentence',
            'nomor_soal_mulai'   => 'required|integer|min:1',
            'nomor_soal_selesai' => 'required|integer|gte:nomor_soal_mulai',
        ]);

        // Cek overlap
        $overlap = ModulSoal::where('paket_id',$paketId)
            ->where(function($q) use ($request) {
                $q->whereBetween('nomor_soal_mulai',  [$request->nomor_soal_mulai, $request->nomor_soal_selesai])
                  ->orWhereBetween('nomor_soal_selesai',[$request->nomor_soal_mulai, $request->nomor_soal_selesai]);
            })->exists();

        if ($overlap)
            return back()->withInput()
                ->with('error',"Rentang No.{$request->nomor_soal_mulai}–{$request->nomor_soal_selesai} sudah dipakai modul lain.");

        $modul = ModulSoal::create([
            'paket_id'           => $paketId,
            'grup_id'            => $grupId,
            'created_by'         => auth()->id(),
            'tipe_modul'         => $request->tipe_modul,
            'judul'              => $request->judul,
            'nomor_soal_mulai'   => $request->nomor_soal_mulai,
            'nomor_soal_selesai' => $request->nomor_soal_selesai,
            'urutan'             => $request->nomor_soal_mulai,
        ]);

        return redirect()->route('admin.paket-builder.modul.input', $modul->id)
            ->with('success','Modul dibuat! Sekarang input konten soal.');
    }

    public function destroyModul($modulId) {
        $modul   = ModulSoal::findOrFail($modulId);
        $paketId = $modul->paket_id;
        DB::transaction(function() use ($modul) {
            BankSoal::where('modul_id',$modul->id)->delete();
            Passage::where('modul_id',$modul->id)->delete();
            $modul->delete();
        });
        return redirect()->route('admin.paket-builder.paket',$paketId)
            ->with('success','Modul dihapus.');
    }

    // ══ INPUT KONTEN ══════════════════════════════════════════════
    public function inputModul($modulId) {
        $modul = ModulSoal::with(['paket','grup','passages.soal','soal'])->findOrFail($modulId);
        if ($modul->isListening()) {
            $audioPaketList = \App\Models\ListeningAudioPaket::where('is_aktif',1)->get();
            return view('admin.paket-builder.input.listening', compact('modul','audioPaketList'));
        }
        if ($modul->isStructure()) {
            return view('admin.paket-builder.input.structure', compact('modul'));
        }
        return match($modul->tipe_modul) {
            'passage'         => view('admin.paket-builder.input.passage',         compact('modul')),
            'missing_letters' => view('admin.paket-builder.input.missing-letters', compact('modul')),
            'image_email'     => view('admin.paket-builder.input.image-email',     compact('modul')),
        };
    }

    // ══ PASSAGE: simpan teks ══════════════════════════════════════
    public function storePassage(Request $request, $modulId) {
        $modul = ModulSoal::findOrFail($modulId);
        if (!$request->filled('teks'))
            return response()->json(['ok'=>false,'msg'=>'Teks passage tidak boleh kosong.']);

        $passage = Passage::create([
            'modul_id'   => $modul->id,
            'paket_id'   => $modul->paket_id,
            'grup_id'    => $modul->grup_id,
            'created_by' => auth()->id(),
            'judul'      => $request->judul,
            'teks'       => $request->teks,
            'tipe_paket' => 'full',
            'is_aktif'   => true,
        ]);
        return response()->json(['ok'=>true,'id'=>$passage->id,'msg'=>'Passage disimpan.']);
    }

    // ══ PASSAGE: simpan soal ══════════════════════════════════════
    public function storeSoalPassage(Request $request, $modulId) {
        $modul = ModulSoal::findOrFail($modulId);
        // Validasi manual agar tetap return JSON
        if (!$request->filled('pertanyaan'))
            return response()->json(['ok'=>false,'msg'=>'Pertanyaan tidak boleh kosong.']);
        if (!$request->filled('jawaban_benar'))
            return response()->json(['ok'=>false,'msg'=>'Pilih jawaban benar.']);

        // Cegah nomor duplikat
        if (BankSoal::where('paket_id',$modul->paket_id)
            ->where('nomor_dalam_paket',$request->nomor_dalam_paket)->exists())
            return response()->json(['ok'=>false,'msg'=>"No.{$request->nomor_dalam_paket} sudah ada di paket ini."]);

        BankSoal::create([
            'modul_id'          => $modul->id,
            'paket_id'          => $modul->paket_id,
            'passage_id'        => $request->passage_id,
            'nomor_dalam_paket' => $request->nomor_dalam_paket,
            'kategori'          => 'reading',
            'tipe_paket'        => 'full',
            'tipe_soal'         => $request->tipe_soal,
            'pertanyaan'        => $request->pertanyaan,
            'highlight_kata'    => $request->highlight_kata,
            'highlight_paragraf'=> $request->highlight_paragraf,
            'pilihan_a'         => $request->pilihan_a ?? '-',
            'pilihan_b'         => $request->pilihan_b ?? '-',
            'pilihan_c'         => $request->pilihan_c ?? '-',
            'pilihan_d'         => $request->pilihan_d ?? '-',
            'jawaban_benar'     => $request->jawaban_benar,
            'tingkat_kesulitan' => $request->tingkat_kesulitan ?? 'medium',
            'is_aktif'          => true,
            'created_by'        => auth()->id(),
        ]);
        return response()->json(['ok'=>true,'msg'=>"Soal No.{$request->nomor_dalam_paket} disimpan."]);
    }

    // ══ LISTENING: simpan soal timeline ══════════════════════════
    public function storeSoalListening(Request $request, $modulId) {
        $modul = ModulSoal::findOrFail($modulId);

        if (!$request->filled('pertanyaan'))
            return response()->json(['ok'=>false,'msg'=>'Pertanyaan wajib diisi.']);
        if (!$request->filled('jawaban_benar'))
            return response()->json(['ok'=>false,'msg'=>'Pilih jawaban benar.']);
        if (!$request->filled('nomor_dalam_paket'))
            return response()->json(['ok'=>false,'msg'=>'Nomor soal wajib diisi.']);

        // Cegah nomor duplikat dalam paket
        if (BankSoal::where('paket_id', $modul->paket_id)
            ->where('nomor_dalam_paket', $request->nomor_dalam_paket)->exists())
            return response()->json(['ok'=>false,'msg'=>"No.{$request->nomor_dalam_paket} sudah ada di paket."]);

        // Simpan audio_paket_id ke modul jika belum ada
        $audioPaketId = $request->audio_paket_id ?: $modul->audio_paket_id;
        if ($audioPaketId && !$modul->audio_paket_id)
            $modul->update(['audio_paket_id' => $audioPaketId]);

        BankSoal::create([
            'modul_id'          => $modul->id,
            'paket_id'          => $modul->paket_id,
            'audio_paket_id'    => $audioPaketId,
            'nomor_dalam_paket' => (int) $request->nomor_dalam_paket,
            'nomor_soal'        => (int) $request->nomor_dalam_paket,
            'order_number'      => (int) $request->nomor_dalam_paket,
            'start_second'      => (int) ($request->start_second ?? 0),
            'kategori'          => 'listening',
            'tipe_paket'        => 'full',
            'tipe_soal'         => 'multiple_choice',
            'pertanyaan'        => $request->pertanyaan,
            'audio_script'      => $request->audio_script,
            'pilihan_a'         => $request->pilihan_a ?: '-',
            'pilihan_b'         => $request->pilihan_b ?: '-',
            'pilihan_c'         => $request->pilihan_c ?: '-',
            'pilihan_d'         => $request->pilihan_d ?: '-',
            'jawaban_benar'     => $request->jawaban_benar,
            'tingkat_kesulitan' => $request->tingkat_kesulitan ?? 'medium',
            'is_aktif'          => true,
            'created_by'        => auth()->id(),
        ]);

        $soalCount = BankSoal::where('modul_id', $modul->id)->count();
        $modul->update(['is_selesai' => $soalCount >= $modul->jumlah_target]);

        return response()->json([
            'ok'  => true,
            'msg' => "Soal No.{$request->nomor_dalam_paket} disimpan (detik: {$request->start_second}).",
        ]);
    
    }

        public function storeMissingLetters(Request $request, $modulId) {
        $modul = ModulSoal::findOrFail($modulId);

        // Selalu return JSON (request dari fetch/AJAX)
        if (!$request->filled('fill_text'))
            return response()->json(['ok'=>false,'msg'=>'Teks tidak boleh kosong.']);

        preg_match_all('/\[([^\]]+)\]/', $request->fill_text, $m);
        $answers = $m[1];
        $jumlah  = count($answers);
        $target  = $modul->jumlah_target;

        if ($jumlah === 0)
            return response()->json(['ok'=>false,'msg'=>'Tidak ada blank [...] ditemukan di teks.']);

        // Update rentang nomor soal sesuai jumlah blank aktual
        $nomorSelesai = $modul->nomor_soal_mulai + $jumlah - 1;
        $modul->update(['nomor_soal_selesai' => $nomorSelesai]);

        DB::transaction(function() use ($modul, $request, $answers) {
            BankSoal::where('modul_id',$modul->id)->delete();
            BankSoal::create([
                'modul_id'          => $modul->id,
                'paket_id'          => $modul->paket_id,
                'nomor_dalam_paket' => $modul->nomor_soal_mulai,
                'kategori'          => 'reading',
                'tipe_paket'        => 'full',
                'tipe_soal'         => 'fill_missing_letters',
                'pertanyaan'        => 'Fill in the missing letters in the paragraph.',
                'fill_text'         => $request->fill_text,
                'jawaban_benar'     => implode('|', $answers),
                'pilihan_a'=>'-','pilihan_b'=>'-','pilihan_c'=>'-','pilihan_d'=>'-',
                'tingkat_kesulitan' => $request->tingkat_kesulitan ?? 'medium',
                'is_aktif'          => true,
                'created_by'        => auth()->id(),
            ]);
            $modul->update(['is_selesai'=>true]);
        });

        return response()->json(['ok'=>true,'jumlah'=>$jumlah,
            'msg'=>"{$jumlah} blank disimpan (No.{$modul->nomor_soal_mulai}–{$modul->nomor_soal_selesai}).",
            'redirect'=>route('admin.paket-builder.paket',$modul->paket_id)]);
    }

    // ══ IMAGE / EMAIL ══════════════════════════════════════════════
    public function storeImageEmail(Request $request, $modulId) {
        $modul = ModulSoal::findOrFail($modulId);
        $request->validate([
            'nomor_dalam_paket' => 'required|integer|min:1',
            'pertanyaan'        => 'required|string',
            'pilihan_a'         => 'required|string',
            'pilihan_b'         => 'required|string',
            'pilihan_c'         => 'required|string',
            'pilihan_d'         => 'required|string',
            'jawaban_benar'     => 'required|in:a,b,c,d',
        ]);

        if (BankSoal::where('paket_id',$modul->paket_id)
            ->where('nomor_dalam_paket',$request->nomor_dalam_paket)->exists())
            return response()->json(['ok'=>false,'msg'=>"No.{$request->nomor_dalam_paket} sudah ada."]);

        // Gambar dipakai dari passage modul ini (upload 1x)
        $passage = Passage::where('modul_id',$modul->id)->first();

        BankSoal::create([
            'modul_id'          => $modul->id,
            'paket_id'          => $modul->paket_id,
            'passage_id'        => $passage?->id,
            'nomor_dalam_paket' => $request->nomor_dalam_paket,
            'kategori'          => 'reading',
            'tipe_paket'        => 'full',
            'tipe_soal'         => 'email_reading',
            'pertanyaan'        => $request->pertanyaan,
            'pilihan_a'         => $request->pilihan_a,
            'pilihan_b'         => $request->pilihan_b,
            'pilihan_c'         => $request->pilihan_c,
            'pilihan_d'         => $request->pilihan_d,
            'jawaban_benar'     => $request->jawaban_benar,
            'tingkat_kesulitan' => $request->tingkat_kesulitan ?? 'medium',
            'is_aktif'          => true,
            'created_by'        => auth()->id(),
        ]);
        return response()->json(['ok'=>true,'msg'=>"Soal No.{$request->nomor_dalam_paket} disimpan."]);
    }

    // ══ UPLOAD GAMBAR untuk modul image_email ════════════════════
    public function uploadGambar(Request $request, $modulId) {
        $modul = ModulSoal::findOrFail($modulId);
        $request->validate(['gambar'=>'required|image|max:5120']);
        $path = $request->file('gambar')->store('soal/images','public');

        // Simpan di passage (1 gambar per modul)
        Passage::updateOrCreate(
            ['modul_id'=>$modul->id],
            ['modul_id'=>$modul->id,'paket_id'=>$modul->paket_id,'grup_id'=>$modul->grup_id,
             'created_by'=>auth()->id(),'judul'=>'Gambar Modul '.$modul->id,
             'teks'=>'','image_url'=>$path,'tipe_paket'=>'full','is_aktif'=>true]
        );
        return response()->json(['ok'=>true,'url'=>asset('storage/'.$path),'msg'=>'Gambar diupload.']);
    }

    public function storeSoalStructure(Request $request, $modulId) {
        $modul = ModulSoal::findOrFail($modulId);
        if (!$request->filled('pertanyaan'))
            return response()->json(['ok'=>false,'msg'=>'Pertanyaan tidak boleh kosong.']);
        if (!$request->filled('nomor_dalam_paket'))
            return response()->json(['ok'=>false,'msg'=>'Nomor soal wajib diisi.']);

        $tipe = $request->tipe_soal ?? 'best_response';
        if ($tipe==='best_response' && !$request->filled('jawaban_benar'))
            return response()->json(['ok'=>false,'msg'=>'Pilih jawaban benar.']);

        if (BankSoal::where('paket_id',$modul->paket_id)
            ->where('nomor_dalam_paket',$request->nomor_dalam_paket)->exists())
            return response()->json(['ok'=>false,'msg'=>"No.{$request->nomor_dalam_paket} sudah ada."]);

        BankSoal::create([
            'modul_id'=>$modul->id,'paket_id'=>$modul->paket_id,
            'nomor_dalam_paket'=>(int)$request->nomor_dalam_paket,
            'nomor_soal'=>(int)$request->nomor_dalam_paket,
            'kategori'=>'structure','tipe_paket'=>'full','tipe_soal'=>$tipe,
            'sub_bagian'=>$request->sub_bagian ?? 'completion',
            'pertanyaan'=>$request->pertanyaan,
            'pilihan_a'=>$request->pilihan_a??'-','pilihan_b'=>$request->pilihan_b??'-',
            'pilihan_c'=>$request->pilihan_c??'-','pilihan_d'=>$request->pilihan_d??'-',
            'jawaban_benar'=>$request->jawaban_benar??'-',
            'arrange_words'=>$request->arrange_words,
            'pembahasan'=>$request->pembahasan,
            'tingkat_kesulitan'=>$request->tingkat_kesulitan??'medium',
            'is_aktif'=>true,'created_by'=>auth()->id(),
        ]);
        $modul->update(['is_selesai'=>BankSoal::where('modul_id',$modul->id)->count()>=$modul->jumlah_target]);
        return response()->json(['ok'=>true,'msg'=>"Soal No.{$request->nomor_dalam_paket} disimpan."]);
    }

    // ══ DELETE SOAL ═══════════════════════════════════════════════
    public function destroySoal($soalId) {
        $soal = BankSoal::findOrFail($soalId);
        $paketId = $soal->paket_id;
        $soal->delete();
        return response()->json(['ok'=>true,'paket_id'=>$paketId]);
    }

    // ══ SELESAIKAN PAKET ══════════════════════════════════════════
    public function previewPaket($paketId) {
        $paket = PaketSoal::findOrFail($paketId);
        $soalGlobal = BankSoal::with(['passage','modul'])
            ->where('paket_id', $paketId)
            ->orderBy('nomor_dalam_paket')->get();
        return view('admin.paket-builder.preview', compact('paket','soalGlobal'));
    }

    public function selesaikanPaket($paketId) {
        $paket = PaketSoal::findOrFail($paketId);
        $paket->update(['status'=>'valid','is_aktif'=>true]);
        return back()->with('success','Paket soal selesai dan diaktifkan!');
    }
}
