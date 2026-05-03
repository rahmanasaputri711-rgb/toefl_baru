<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankSoal;
use App\Models\GrupSoal;
use App\Services\AudioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BankSoalController extends Controller
{
    /* ── Statistik sidebar ─────────────────────────────── */
    private function stats(): array {
        return [
            'total'     => BankSoal::count(),
            'listening' => BankSoal::where('kategori','listening')->count(),
            'structure' => BankSoal::where('kategori','structure')->count(),
            'reading'   => BankSoal::where('kategori','reading')->count(),
            'aktif'     => BankSoal::where('is_aktif',1)->count(),
            'belum_pakai' => BankSoal::where('pakai_count',0)->count(),
        ];
    }

    /* ─────────────────────────────────────────────────────
       INDEX
    ───────────────────────────────────────────────────── */
    public function index(Request $request)
    {
        $q = BankSoal::with(['grupSoal','creator']);

        if ($request->filled('kategori'))   $q->where('kategori', $request->kategori);
        if ($request->filled('part'))       $q->where('part', $request->part);
        if ($request->filled('kesulitan'))  $q->where('tingkat_kesulitan', $request->kesulitan);
        if ($request->filled('aktif'))      $q->where('is_aktif', $request->aktif);
        if ($request->filled('pakai')) {
            if ($request->pakai === 'belum') $q->where('pakai_count', 0);
            if ($request->pakai === 'sudah') $q->where('pakai_count', '>', 0);
        }
        if ($request->filled('search'))
            $q->where('pertanyaan','like','%'.$request->search.'%');

        // Urutkan: kategori → part → nomor_soal
        $q->orderBy('kategori')->orderBy('part')->orderByRaw('COALESCE(nomor_soal,9999)');

        $soal = $q->paginate(20)->withQueryString();

        return view('admin.soal.index', array_merge(
            ['soal' => $soal],
            $this->stats()
        ));
    }

    /* ─────────────────────────────────────────────────────
       CREATE / STORE
    ───────────────────────────────────────────────────── */
    public function create(Request $request)
    {
        // Jika ada grup_id di query string → buat soal langsung di grup itu
        $grup = $request->filled('grup_id')
            ? GrupSoal::find($request->grup_id)
            : null;

        return view('admin.soal.create', compact('grup'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori'          => 'required|in:reading,listening,structure',
            'part'              => 'nullable|in:A,B,C',
            'tingkat_kesulitan' => 'required|in:easy,medium,hard',
            'pertanyaan'        => 'required|string|min:5',
            'pilihan_a'         => 'required|string',
            'pilihan_b'         => 'required|string',
            'pilihan_c'         => 'required|string',
            'pilihan_d'         => 'required|string',
            'jawaban_benar'     => 'required|in:a,b,c,d',
            'audio_file'        => 'nullable|file|mimes:mp3,ogg,wav,m4a,mpeg,webm|max:20480',
        ]);

        $data = $request->only([
            'kategori','part','nomor_soal','tingkat_kesulitan','pertanyaan',
            'pilihan_a','pilihan_b','pilihan_c','pilihan_d',
            'jawaban_benar','pembahasan','passage_teks','group_id','grup_soal_id',
        ]);

        $data['created_by']     = auth()->id();
        $data['untuk_tes_full'] = $request->boolean('untuk_tes_full', true);
        $data['is_aktif']       = $request->boolean('is_aktif', true);

        if ($request->hasFile('audio_file') && $request->file('audio_file')->isValid()) {
            $data['audio_url'] = $request->file('audio_file')->store('audio','public');
        }

        $soal = BankSoal::create($data);

        // Sync grup count
        if ($soal->grup_soal_id) {
            GrupSoal::find($soal->grup_soal_id)?->syncCount();
        }

        return redirect()->route('admin.soal.index')
            ->with('success', 'Soal berhasil ditambahkan ke bank soal.');
    }

    /* ─────────────────────────────────────────────────────
       EDIT / UPDATE
    ───────────────────────────────────────────────────── */
    public function edit($id)
    {
        $soal = BankSoal::with('grupSoal')->findOrFail($id);
        return view('admin.soal.edit', compact('soal'));
    }

    public function update(Request $request, $id)
    {
        $soal = BankSoal::findOrFail($id);
        $oldGrup = $soal->grup_soal_id;

        $request->validate([
            'kategori'          => 'required|in:reading,listening,structure',
            'part'              => 'nullable|in:A,B,C',
            'tingkat_kesulitan' => 'required|in:easy,medium,hard',
            'pertanyaan'        => 'required|string|min:5',
            'pilihan_a'         => 'required|string',
            'pilihan_b'         => 'required|string',
            'pilihan_c'         => 'required|string',
            'pilihan_d'         => 'required|string',
            'jawaban_benar'     => 'required|in:a,b,c,d',
            'audio_file'        => 'nullable|file|mimes:mp3,ogg,wav,m4a,mpeg,webm|max:20480',
        ]);

        $data = $request->only([
            'kategori','part','nomor_soal','tingkat_kesulitan','pertanyaan',
            'pilihan_a','pilihan_b','pilihan_c','pilihan_d',
            'jawaban_benar','pembahasan','passage_teks','group_id','grup_soal_id',
        ]);

        $data['untuk_tes_full'] = $request->boolean('untuk_tes_full', true);
        $data['is_aktif']       = $request->boolean('is_aktif', true);

        if ($request->boolean('hapus_audio') && $soal->audio_url) {
            AudioService::delete($soal->audio_url);
            $data['audio_url'] = null;
        }

        if ($request->hasFile('audio_file') && $request->file('audio_file')->isValid()) {
            if ($soal->audio_url) AudioService::delete($soal->audio_url);
            $data['audio_url'] = $request->file('audio_file')->store('audio','public');
        }

        $soal->update($data);

        // Sync grup count (lama dan baru)
        if ($oldGrup) GrupSoal::find($oldGrup)?->syncCount();
        if ($soal->grup_soal_id && $soal->grup_soal_id !== $oldGrup) {
            GrupSoal::find($soal->grup_soal_id)?->syncCount();
        }

        return redirect()->route('admin.soal.index')
            ->with('success', "Soal #{$soal->id} berhasil diperbarui.");
    }

    /* ─────────────────────────────────────────────────────
       DELETE / TOGGLE
    ───────────────────────────────────────────────────── */
    public function destroy($id)
    {
        $soal = BankSoal::findOrFail($id);
        $grup = $soal->grup_soal_id;
        if ($soal->audio_url) AudioService::delete($soal->audio_url);
        $soal->delete();
        if ($grup) GrupSoal::find($grup)?->syncCount();
        return redirect()->route('admin.soal.index')->with('success','Soal dihapus.');
    }

    public function toggleAktif($id)
    {
        $soal = BankSoal::findOrFail($id);
        $soal->update(['is_aktif' => !$soal->is_aktif]);
        return back()->with('success', "Soal #{$id} " . ($soal->fresh()->is_aktif?'diaktifkan':'dinonaktifkan') . '.');
    }

    /* ─────────────────────────────────────────────────────
       PREVIEW (JSON untuk floating modal)
    ───────────────────────────────────────────────────── */
    public function preview($id)
    {
        $soal = BankSoal::with('grupSoal')->findOrFail($id);
        return response()->json([
            'id'               => $soal->id,
            'kategori'         => $soal->kategori,
            'part'             => $soal->part,
            'part_label'       => $soal->part_label,
            'tingkat_kesulitan'=> $soal->tingkat_kesulitan,
            'pertanyaan'       => $soal->pertanyaan,
            'passage_teks'     => $soal->passage_teks,
            'pilihan_a'        => $soal->pilihan_a,
            'pilihan_b'        => $soal->pilihan_b,
            'pilihan_c'        => $soal->pilihan_c,
            'pilihan_d'        => $soal->pilihan_d,
            'jawaban_benar'    => $soal->jawaban_benar,
            'pembahasan'       => $soal->pembahasan,
            'audio_url'        => AudioService::resolveUrl($soal->audio_url),
            'audio_filename'   => $soal->audio_url ? basename($soal->audio_url) : null,
            'grup'             => $soal->grupSoal?->judul,
            'untuk_tes_full'   => $soal->untuk_tes_full,
            'pakai_count'      => $soal->pakai_count,
            'is_aktif'         => $soal->is_aktif,
            'edit_url'         => route('admin.soal.edit', $soal->id),
        ]);
    }
}
