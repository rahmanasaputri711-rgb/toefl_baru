<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Passage;
use App\Models\BankSoal;
use Illuminate\Http\Request;

class PassageController extends Controller
{
    /** Daftar semua passage + soal-soalnya */
    public function index(Request $request)
    {
        $query = Passage::with(['soal' => fn($q) => $q->orderBy('nomor_soal')])
            ->orderBy('urutan')->orderBy('created_at','desc');

        if ($request->filled('tipe_paket'))
            $query->where('tipe_paket', $request->tipe_paket);

        if ($request->filled('search'))
            $query->where('judul','like','%'.$request->search.'%');

        $passages = $query->get();

        $stats = [
            'total_passage' => Passage::count(),
            'total_soal'    => BankSoal::where('kategori','reading')->count(),
            'full'          => Passage::where('tipe_paket','full')->count(),
            'simulasi'      => Passage::where('tipe_paket','simulasi')->count(),
        ];

        return view('admin.passage.index', compact('passages','stats'));
    }

    /** Form buat passage baru */
    public function create()
    {
        return view('admin.passage.create');
    }

    /** Simpan passage baru */
    public function store(Request $request)
    {
        $request->validate([
            'judul'      => 'required|string|max:255',
            'teks'       => 'required|string|min:50',
            'tipe_paket' => 'required|in:praktik,mini,simulasi,full',
        ], [
            'teks.min' => 'Teks passage minimal 50 karakter.',
        ]);

        $passage = Passage::create([
            'judul'      => trim($request->judul),
            'teks'       => $request->teks,
            'tipe_paket' => $request->tipe_paket,
            'created_by' => auth()->id(),
            'is_aktif'   => true,
            'urutan'     => Passage::where('tipe_paket',$request->tipe_paket)->count() + 1,
        ]);

        return redirect()->route('admin.passage.show', $passage->id)
            ->with('success', 'Passage berhasil dibuat! Sekarang tambahkan soal-soal di bawah.');
    }

    /** Detail passage + daftar soal + form tambah soal */
    public function show($id)
    {
        $passage = Passage::with(['soal' => fn($q) =>
            $q->orderBy('nomor_soal')])->findOrFail($id);

        $tipeSoalMap   = BankSoal::$tipeSoalMap;
        $nomorBerikut  = ($passage->soal->max('nomor_soal') ?? 0) + 1;

        return view('admin.passage.show', compact('passage','tipeSoalMap','nomorBerikut'));
    }

    /** Form edit passage */
    public function edit($id)
    {
        $passage = Passage::with('soal')->findOrFail($id);
        return view('admin.passage.edit', compact('passage'));
    }

    /** Update passage */
    public function update(Request $request, $id)
    {
        $passage = Passage::findOrFail($id);
        $request->validate([
            'judul' => 'required|string|max:255',
            'teks'  => 'required|string|min:50',
        ]);
        $passage->update($request->only('judul','teks','tipe_paket','urutan'));
        return back()->with('success', 'Passage berhasil diperbarui.');
    }

    /** Hapus passage (cascade hapus soal juga) */
    public function destroy($id)
    {
        Passage::findOrFail($id)->delete();
        return redirect()->route('admin.passage.index')
            ->with('success','Passage dan semua soalnya berhasil dihapus.');
    }

    /** ── TAMBAH SOAL KE PASSAGE ── */
    public function storeSoal(Request $request, $passageId)
    {
        $passage = Passage::findOrFail($passageId);

        // Validasi dasar
        $request->validate([
            'tipe_soal'        => 'required|in:multiple_choice,vocabulary,insert_sentence,click_sentence,prose_summary,fill_missing_letters',
            'pertanyaan'       => 'nullable|string',
            'nomor_soal'       => 'required|integer|min:1',
            'tingkat_kesulitan'=> 'required|in:easy,medium,hard',
            'pilihan_a'        => 'nullable|string',
            'pilihan_b'        => 'nullable|string',
            'pilihan_c'        => 'nullable|string',
            'pilihan_d'        => 'nullable|string',
        ]);

        $tipe = $request->tipe_soal;

        // Validasi tambahan per tipe
        if ($tipe === 'vocabulary') {
            $request->validate([
                'highlight_kata'      => 'required|string|max:100',
                'highlight_paragraf'  => 'required|integer|min:1',
                'jawaban_benar'       => 'required|in:a,b,c,d',
            ]);
        } elseif ($tipe === 'insert_sentence') {
            $request->validate([
                'insert_sentence_teks' => 'required|string',
                'jawaban_benar'        => 'required|in:a,b,c,d',
            ]);
        } elseif ($tipe === 'click_sentence') {
            $request->validate([
                'jawaban_benar' => 'required|string',
            ]);
        } elseif ($tipe === 'prose_summary') {
            $request->validate([
                'pilihan_e'              => 'required|string',
                'pilihan_f'              => 'required|string',
                'jawaban_benar_multiple' => 'required|string', // format: "a,c,e"
            ]);
        } elseif ($tipe === 'fill_missing_letters') {
            $request->validate([
                'fill_text' => 'required|string|min:20',
            ]);
        } else {
            $request->validate(['jawaban_benar' => 'required|in:a,b,c,d']);
        }

        BankSoal::create([
            'passage_id'           => $passage->id,
            'kategori'             => 'reading',
            'tipe_paket'           => $passage->tipe_paket,
            'tipe_soal'            => $tipe,
            'nomor_soal'           => $request->nomor_soal,
            'pertanyaan'           => $request->pertanyaan,
            'tingkat_kesulitan'    => $request->tingkat_kesulitan,
            'highlight_kata'       => $request->highlight_kata,
            'highlight_paragraf'   => $request->highlight_paragraf,
            'insert_sentence_teks' => $request->insert_sentence_teks,
            'pilihan_a'            => $request->pilihan_a,
            'pilihan_b'            => $request->pilihan_b,
            'pilihan_c'            => $request->pilihan_c,
            'pilihan_d'            => $request->pilihan_d,
            'pilihan_e'            => $request->pilihan_e,
            'pilihan_f'            => $request->pilihan_f,
            'fill_text'            => $request->fill_text,
            'jawaban_benar'        => $request->jawaban_benar ?? 'a',
            'jawaban_benar_multiple' => $request->jawaban_benar_multiple,
            'pembahasan'           => $request->pembahasan,
            'skill_materi'         => $request->skill_materi,
            'is_aktif'             => true,
            'created_by'           => auth()->id(),
        ]);

        return back()->with('success', "Soal No.{$request->nomor_soal} berhasil ditambahkan.");
    }

    /** Hapus 1 soal dari passage */
    public function destroySoal($soalId)
    {
        $soal    = BankSoal::findOrFail($soalId);
        $passage = $soal->passage_id;
        $soal->delete();
        return redirect()->route('admin.passage.show', $passage)
            ->with('success','Soal berhasil dihapus.');
    }
}
