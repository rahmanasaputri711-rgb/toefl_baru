<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\EvaluasiTes;
use App\Models\SesiTes;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class EvaluasiController extends Controller
{
    public function index()
    {
        $evaluasi = EvaluasiTes::with(['sesiTes'])->latest()->paginate(10);
        $sesiList = SesiTes::where('tipe_tes','full')->orderByDesc('waktu_mulai')->get();
        return view('admin.evaluasi.index', compact('evaluasi','sesiList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sesi_id'     => 'required|exists:sesi_tes,id',
            'judul'       => 'required|string|max:255',
            'catatan'     => 'required|string',
        ]);

        $eval = EvaluasiTes::create([
            'sesi_id'     => $request->sesi_id,
            'admin_id'    => auth()->id(),
            'judul'       => $request->judul,
            'catatan'     => $request->catatan,
            'rekomendasi' => $request->rekomendasi,
            'untuk_user'  => $request->has('untuk_user') ? 1 : 0,
            'is_published'=> 0,
        ]);

        return redirect()->route('admin.evaluasi.index')->with('success','Evaluasi berhasil disimpan.');
    }

    public function publish($id)
    {
        $eval = EvaluasiTes::with(['sesiTes'])->findOrFail($id);
        $eval->update(['is_published' => 1, 'published_at' => now()]);

        // Notif ke semua peserta sesi tsb
        $peserta = \App\Models\PendaftaranTes::where('sesi_id',$eval->sesi_id)
            ->where('status_pendaftaran','dikonfirmasi')->get();
        foreach ($peserta as $p) {
            Notifikasi::create([
                'user_id'      => $p->user_id,
                'judul'        => 'Evaluasi Tes Tersedia',
                'pesan'        => 'Evaluasi untuk sesi "'.$eval->sesiTes->judul.'" telah dipublikasikan.',
                'tipe'         => 'evaluasi',
                'is_important' => 1,
            ]);
        }

        return back()->with('success','Evaluasi dipublikasikan.');
    }

    public function destroy($id)
    {
        EvaluasiTes::findOrFail($id)->delete();
        return back()->with('success','Evaluasi dihapus.');
    }
}
