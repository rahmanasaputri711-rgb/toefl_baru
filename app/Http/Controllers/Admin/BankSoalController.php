<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankSoal;
use App\Models\GrupSoal;
use App\Models\Passage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BankSoalController extends Controller
{
    public function index(Request $request)
    {
        $q = BankSoal::with('grupSoal','passage');
        if ($request->filled('kategori'))      $q->where('kategori', $request->kategori);
        // Bank Soal hanya untuk Tes Full
        $q->where('untuk_tes_full', 1);
        if ($request->filled('tipe_paket'))    $q->where('tipe_paket', $request->tipe_paket);
        if ($request->filled('kesulitan'))     $q->where('tingkat_kesulitan', $request->kesulitan);
        if ($request->filled('sub_bagian'))    $q->where('sub_bagian', $request->sub_bagian);
        if ($request->filled('search'))
            $q->where('pertanyaan','like','%'.$request->search.'%');

        $soalList = $q->orderBy('kategori')->orderBy('nomor_soal')->get();

        // Statistik per section & tipe
        $stats = [
            'total'     => BankSoal::count(),
            'listening' => BankSoal::where('kategori','listening')->where('is_aktif',1)->count(),
            'structure' => BankSoal::where('kategori','structure')->where('is_aktif',1)->count(),
            'reading'   => BankSoal::where('kategori','reading')->where('is_aktif',1)->count(),
            'aktif'     => BankSoal::where('is_aktif',1)->count(),
            'nonaktif'  => BankSoal::where('is_aktif',0)->count(),
        ];

        $passages = Passage::orderBy('urutan')->get();
        return view('admin.soal.index', compact('soalList','stats','passages'));
    }

    public function create()
    {
        $passages       = Passage::where('is_aktif',1)->orderBy('urutan')->get();
        $audioPaketList = \App\Models\ListeningAudioPaket::where('is_aktif',1)->get();
        $tipeSoal       = \App\Models\BankSoal::TIPE_PER_SECTION;
        $tipePaket      = \App\Models\BankSoal::TIPE_PAKET;
        return view('admin.soal.create', compact('passages','audioPaketList','tipeSoal','tipePaket'));
    }

    public function store(Request $request)
    {
        $rules = [
            'kategori'          => 'required|in:listening,structure,reading',
            'tipe_paket'        => 'required|in:full', // Bank Soal hanya untuk Tes Full
            'tingkat_kesulitan' => 'required|in:easy,medium,hard',
            'nomor_soal'        => 'nullable|integer|min:1|max:140',
            'pertanyaan'        => 'required|string',
            'pilihan_a'         => 'required|string',
            'pilihan_b'         => 'required|string',
            'pilihan_c'         => 'required|string',
            'pilihan_d'         => 'required|string',
            'jawaban_benar'     => 'required|in:a,b,c,d',
            'skill_materi'      => 'nullable|string|max:100',
            'pembahasan'        => 'nullable|string',
            'script_audio'      => 'nullable|string',
            'audio_script'      => 'nullable|string',
            'tipe_soal'         => 'nullable|in:multiple_choice,vocabulary,insert_sentence,click_sentence,prose_summary',
            'group_id'          => 'nullable|string|max:50',
            'passage_id'        => 'nullable|exists:passages,id',
            'sub_bagian'        => 'nullable|in:structure,written_expression',
            'audio_url'         => 'nullable|file|mimes:mp3,wav,ogg|max:51200',
        ];

        $validated = $request->validate($rules);

        $audioPath = null;
        if ($request->hasFile('audio_url')) {
            $audioPath = $request->file('audio_url')->store('audio/soal', 'public');
        }

        BankSoal::create(array_merge(
            collect($validated)->except('audio_url')->toArray(),
            [
                'created_by'  => auth()->id(),
                'audio_url'   => $audioPath,
                'is_aktif'    => true,
                'tipe_soal'   => $request->tipe_soal ?? 'multiple_choice',
                'audio_script'=> $request->audio_script ?? $request->script_audio,
            ]
        ));

        return redirect()->route('admin.soal.index')
            ->with('success', 'Soal berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $soal     = BankSoal::findOrFail($id);
        $grupSoal = GrupSoal::orderBy('nama')->get();
        $passages = Passage::where('is_aktif',1)->orderBy('urutan')->get();
        return view('admin.soal.edit', compact('soal','grupSoal','passages'));
    }

    public function update(Request $request, $id)
    {
        $soal = BankSoal::findOrFail($id);

        $validated = $request->validate([
            'kategori'          => 'required|in:listening,structure,reading',
            'tipe_paket'        => 'required|in:full', // Bank Soal hanya untuk Tes Full
            'tingkat_kesulitan' => 'required|in:easy,medium,hard',
            'nomor_soal'        => 'nullable|integer|min:1|max:140',
            'pertanyaan'        => 'required|string',
            'pilihan_a'         => 'required|string',
            'pilihan_b'         => 'required|string',
            'pilihan_c'         => 'required|string',
            'pilihan_d'         => 'required|string',
            'jawaban_benar'     => 'required|in:a,b,c,d',
            'skill_materi'      => 'nullable|string|max:100',
            'pembahasan'        => 'nullable|string',
            'script_audio'      => 'nullable|string',
            'audio_script'      => 'nullable|string',
            'tipe_soal'         => 'nullable|in:multiple_choice,vocabulary,insert_sentence,click_sentence,prose_summary',
            'group_id'          => 'nullable|string|max:50',
            'passage_id'        => 'nullable|exists:passages,id',
            'sub_bagian'        => 'nullable|in:structure,written_expression',
        ]);

        $soal->update($validated);
        return back()->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        BankSoal::findOrFail($id)->delete();
        return back()->with('success', 'Soal dihapus.');
    }

    public function toggleAktif($id)
    {
        $soal = BankSoal::findOrFail($id);
        $soal->update(['is_aktif' => !$soal->is_aktif]);
        return back()->with('success', 'Status soal diperbarui.');
    }

    /** Preview soal di modal */
    public function preview($id)
    {
        $soal = BankSoal::with('passage')->findOrFail($id);
        return response()->json($soal);
    }
}
