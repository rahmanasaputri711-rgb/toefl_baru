<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{ListeningAudioPaket, BankSoal, PaketSoal};
use Illuminate\Http\Request;

class ListeningController extends Controller
{
    public function index()
    {
        $audioFullList  = ListeningAudioPaket::where('tipe_upload','paket')
            ->withCount('soalList')->orderByDesc('created_at')->get();

        $audioModulList = ListeningAudioPaket::where('tipe_upload','modul')
            ->with('paketSoal')->orderBy('paket_soal_id')->orderBy('urutan_modul')->get()
            ->groupBy('paket_soal_id');

        return view('admin.listening.index', compact('audioFullList','audioModulList'));
    }

    public function create()
    {
        $paketList = PaketSoal::orderByDesc('created_at')->get();
        return view('admin.listening.create', compact('paketList'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama'        => 'required|string|max:200',
                'tipe_paket'  => 'required|in:full', // Bank Soal hanya Tes Full
                'tipe_upload' => 'nullable|in:paket,modul',
                'audio'       => 'required|file|mimetypes:audio/mpeg,audio/wav,audio/ogg,audio/mp4,audio/x-m4a|max:204800',
            ]);

            $audioPath   = $request->file('audio')->store('audio/listening','public');
            $tipeUpload  = $request->tipe_upload ?? 'paket';

            $offset = 0;
            if ($tipeUpload === 'modul' && $request->paket_soal_id) {
                $offset = (int) ListeningAudioPaket::where('tipe_upload','modul')
                    ->where('paket_soal_id', $request->paket_soal_id)
                    ->sum('durasi_detik');
            }

            $audio = ListeningAudioPaket::create([
                'nama'          => trim($request->nama),
                'tipe_paket'    => $request->tipe_paket,
                'tipe_upload'   => $tipeUpload,
                'paket_soal_id' => $request->paket_soal_id,
                'urutan_modul'  => $request->urutan_modul ?? 0,
                'offset_detik'  => $offset,
                'keterangan'    => $request->keterangan,
                'audio_url'     => $audioPath,
                'durasi_detik'  => (int)($request->durasi_detik ?? 0),
                'jumlah_soal'   => 0,
                'is_aktif'      => true,
                'created_by'    => auth()->id(),
            ]);

            return response()->json([
                'ok'       => true,
                'redirect' => route('admin.listening.show', $audio->id),
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'ok'    => false,
                'error' => $e->getMessage(),
                'line'  => $e->getLine(),
            ], 500);
        }
    }

    public function show($id)
    {
        $paket    = ListeningAudioPaket::with(['soalList'=>fn($q)=>$q->orderBy('order_number')])
            ->findOrFail($id);
        $soalList = $paket->soalList;

        $audioGabungan = null;
        if (($paket->tipe_upload ?? 'paket') === 'modul' && $paket->paket_soal_id) {
            $audioGabungan = ListeningAudioPaket::where('tipe_upload','modul')
                ->where('paket_soal_id', $paket->paket_soal_id)
                ->orderBy('urutan_modul')->get();
        }

        return view('admin.listening.show', compact('paket','soalList','audioGabungan'));
    }

    public function storeSoal(Request $request, $paketId)
    {
        $paket = ListeningAudioPaket::findOrFail($paketId);
        if ($request->isJson()) $request->merge($request->json()->all());

        $request->validate([
            'pertanyaan'     => 'required|string',
            'pilihan_a'      => 'required|string',
            'pilihan_b'      => 'required|string',
            'pilihan_c'      => 'required|string',
            'pilihan_d'      => 'required|string',
            'jawaban_benar'  => 'required|in:a,b,c,d',
            'start_second'   => 'required|numeric|min:0',
            'audio_end'      => 'required|numeric|min:0',
            'pause_duration' => 'nullable|numeric|min:5|max:120',
            'order_number'   => 'required|numeric|min:1',
        ]);

        $offsetDetik       = (int)($paket->offset_detik ?? 0);
        $startSecond       = $offsetDetik + (int)$request->start_second;
        $audioEnd          = $offsetDetik + (int)$request->audio_end;
        $pauseDuration     = (int)($request->pause_duration ?? 15);

        // Hitung session_resume_time: audio_end + pause_duration
        // Ini virtual timeline — waktu saat audio lanjut setelah pause jawab
        $sessionResumeTime = $audioEnd + $pauseDuration;

        BankSoal::create([
            'audio_paket_id'     => $paket->id,
            'kategori'           => 'listening',
            'tipe_paket'         => $paket->tipe_paket,
            'tipe_soal'          => 'multiple_choice',
            'pertanyaan'         => $request->pertanyaan,
            'pilihan_a'          => $request->pilihan_a,
            'pilihan_b'          => $request->pilihan_b,
            'pilihan_c'          => $request->pilihan_c,
            'pilihan_d'          => $request->pilihan_d,
            'jawaban_benar'      => $request->jawaban_benar,
            'start_second'       => $startSecond,
            'audio_end'          => $audioEnd,
            'pause_duration'     => $pauseDuration,
            'session_resume_time'=> $sessionResumeTime,
            'order_number'       => (int)$request->order_number,
            'nomor_soal'         => (int)$request->order_number,
            'audio_script'       => $request->audio_script,
            'tingkat_kesulitan'  => $request->tingkat_kesulitan ?? 'medium',
            'is_aktif'           => true,
            'created_by'         => auth()->id(),
        ]);

        $paket->update(['jumlah_soal'=>BankSoal::where('audio_paket_id',$paket->id)->count()]);
        return response()->json(['ok'=>true,'msg'=>'Soal disimpan.']);
    }

    public function updateDurasi(Request $request, $id)
    {
        if ($request->isJson()) $request->merge($request->json()->all());
        ListeningAudioPaket::findOrFail($id)->update([
            'durasi_detik' => (int)$request->durasi_detik,
        ]);
        return response()->json(['ok'=>true]);
    }

    public function destroySoal($soalId)
    {
        $soal = BankSoal::findOrFail($soalId);
        $pid  = $soal->audio_paket_id;
        $soal->delete();
        ListeningAudioPaket::where('id',$pid)
            ->update(['jumlah_soal'=>BankSoal::where('audio_paket_id',$pid)->count()]);
        return response()->json(['ok'=>true]);
    }

    public function destroy($id)
    {
        $paket = ListeningAudioPaket::findOrFail($id);
        if ($paket->audio_url)
            \Storage::disk('public')->delete($paket->audio_url);
        $paket->delete();
        return redirect()->route('admin.listening.index')
            ->with('success','Paket audio berhasil dihapus.');
    }

    public function infoGabungan($paketSoalId)
    {
        $audioList  = ListeningAudioPaket::where('tipe_upload','modul')
            ->where('paket_soal_id', $paketSoalId)
            ->orderBy('urutan_modul')->get();
        $totalDetik = $audioList->sum('durasi_detik');
        return response()->json([
            'ok'          => true,
            'total_detik' => $totalDetik,
            'total_format'=> sprintf('%d:%02d', intdiv($totalDetik,60), $totalDetik%60),
        ]);
    }
}
