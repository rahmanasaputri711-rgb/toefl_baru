<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaketSoal;
use App\Models\PaketSoalDetail;
use App\Models\BankSoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaketSoalController extends Controller
{
    public function index()
    {
        $paket = PaketSoal::withCount('soal')->latest()->paginate(15);
        return view('admin.paket_soal.index', compact('paket'));
    }

    public function create()
    {
        return view('admin.paket_soal.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:255']);

        $paket = PaketSoal::create([
            'created_by' => auth()->id(),
            'nama'       => $request->nama,
            'deskripsi'  => $request->deskripsi,
            'status'     => 'draft',
        ]);

        return redirect()->route('admin.paket.edit', $paket->id)
            ->with('success', 'Paket dibuat. Silakan tambahkan soal.');
    }

    public function edit(Request $request, $id)
    {
        $paket   = PaketSoal::with(['soal' => fn($q) => $q->orderByPivot('urutan')])->findOrFail($id);
        $soalIds = $paket->soal->pluck('id')->toArray();

        // Query dasar: soal aktif yang belum masuk paket ini
        $base = fn($kat) => BankSoal::where('kategori',$kat)->where('is_aktif',1)
            ->whereNotIn('id', $soalIds);

        // Apply filter dari request
        $applyFilter = function($q) use ($request) {
            if ($request->filled('f_part'))  $q->where('part', $request->f_part);
            if ($request->filled('f_lvl'))   $q->where('tingkat_kesulitan', $request->f_lvl);
            if ($request->f_pakai === '0')   $q->where('pakai_count', 0);
            if ($request->f_pakai === '1')   $q->where('pakai_count', '>', 0);
        };

        $buildQuery = function($kat) use ($base, $applyFilter, $request) {
            if ($request->filled('f_kat') && $request->f_kat !== $kat) return collect();
            $q = $base($kat);
            $applyFilter($q);
            return $q->get(['id','pertanyaan','part','tingkat_kesulitan','pakai_count','audio_url','passage_teks']);
        };

        $bankListening = $buildQuery('listening');
        $bankStructure = $buildQuery('structure');
        $bankReading   = $buildQuery('reading');

        return view('admin.paket_soal.edit', compact(
            'paket','bankListening','bankStructure','bankReading'
        ));
    }

    /** Tambah soal ke paket */
    public function addSoal(Request $request, $id)
    {
        $request->validate(['soal_ids' => 'required|array', 'soal_ids.*' => 'exists:bank_soal,id']);

        $paket   = PaketSoal::findOrFail($id);
        $maxUrut = PaketSoalDetail::where('paket_id',$id)->max('urutan') ?? 0;

        foreach ($request->soal_ids as $soalId) {
            // Cegah duplikat
            PaketSoalDetail::firstOrCreate(
                ['paket_id' => $id, 'soal_id' => $soalId],
                ['urutan' => ++$maxUrut]
            );
        }

        // Update pakai_count soal
        BankSoal::whereIn('id', $request->soal_ids)->increment('pakai_count');

        $paket->validate();
        return back()->with('success', count($request->soal_ids).' soal ditambahkan.');
    }

    /** Hapus soal dari paket */
    public function removeSoal(Request $request, $id)
    {
        $request->validate(['soal_id' => 'required|exists:bank_soal,id']);

        PaketSoalDetail::where('paket_id', $id)
            ->where('soal_id', $request->soal_id)->delete();

        // Kurangi pakai_count (jangan sampai < 0)
        BankSoal::where('id', $request->soal_id)
            ->where('pakai_count', '>', 0)->decrement('pakai_count');

        PaketSoal::findOrFail($id)->validate();
        return back()->with('success', 'Soal dihapus dari paket.');
    }

    /** Validasi manual & simpan paket */
    public function validatePaket($id)
    {
        $paket = PaketSoal::findOrFail($id);
        $paket->validate();

        if ($paket->fresh()->status === 'valid') {
            return back()->with('success', '✅ Paket valid! Listening 50, Structure 40, Reading 50.');
        }
        $msg = "❌ Paket belum valid. "
             . "Listening: {$paket->jumlah_listening}/50, "
             . "Structure: {$paket->jumlah_structure}/40, "
             . "Reading: {$paket->jumlah_reading}/50.";
        return back()->with('error', $msg);
    }

    public function destroy($id)
    {
        $paket = PaketSoal::findOrFail($id);
        // Kembalikan pakai_count
        $soalIds = $paket->soal->pluck('id')->toArray();
        if ($soalIds) {
            BankSoal::whereIn('id', $soalIds)->each(function($s) {
                if ($s->pakai_count > 0) $s->decrement('pakai_count');
            });
        }
        $paket->delete();
        return redirect()->route('admin.paket.index')->with('success','Paket dihapus.');
    }
}
