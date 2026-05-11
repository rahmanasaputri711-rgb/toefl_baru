<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ListeningAudioPaket;
use App\Models\BankSoal;
use Illuminate\Http\Request;

class ListeningController extends Controller
{
    /** Daftar semua paket audio listening */
    public function index()
    {
        $paketList = ListeningAudioPaket::withCount('soalList')
            ->orderByDesc('created_at')->get();
        return view('admin.listening.index', compact('paketList'));
    }

    /** Form buat paket baru + upload audio */
    public function create()
    {
        return view('admin.listening.create');
    }

    /** Simpan paket + audio */
    public function store(Request $request)
    {
        $request->validate([
            'nama'       => 'required|string|max:200',
            'tipe_paket' => 'required|in:full,simulasi,mini,praktik',
            'audio'      => 'required|file|mimes:mp3,wav,ogg,m4a|max:102400',
        ], [
            'audio.max'   => 'File audio maksimal 100MB.',
            'audio.mimes' => 'Format audio harus MP3, WAV, OGG, atau M4A.',
        ]);

        $audioPath = $request->file('audio')->store('audio/listening', 'public');

        $paket = ListeningAudioPaket::create([
            'nama'          => trim($request->nama),
            'tipe_paket'    => $request->tipe_paket,
            'audio_url'     => $audioPath,
            'durasi_detik'  => 0, // akan di-update via JS setelah audio dimuat
            'jumlah_soal'   => 0,
            'is_aktif'      => true,
            'created_by'    => auth()->id(),
        ]);

        return redirect()->route('admin.listening.show', $paket->id)
            ->with('success', 'Audio berhasil diupload! Sekarang tambahkan soal-soal listening.');
    }

    /** Halaman utama: timeline audio + daftar soal */
    public function show($id)
    {
        $paket   = ListeningAudioPaket::with(['soalList'])->findOrFail($id);
        $soalList = $paket->soalList()->orderBy('order_number')->get();
        return view('admin.listening.show', compact('paket', 'soalList'));
    }

    /** Simpan 1 soal listening */
    public function storeSoal(Request $request, $paketId)
    {
        $paket = ListeningAudioPaket::findOrFail($paketId);

        // Handle JSON body dari fetch() di JS
        if ($request->isJson()) {
            $data = $request->json()->all();
            $request->merge($data);
        }

        $request->validate([
            'pertanyaan'    => 'required|string',
            'pilihan_a'     => 'required|string',
            'pilihan_b'     => 'required|string',
            'pilihan_c'     => 'required|string',
            'pilihan_d'     => 'required|string',
            'jawaban_benar' => 'required|in:a,b,c,d',
            'start_second'  => 'required|integer|min:0',
            'order_number'  => 'required|integer|min:1|max:50',
        ]);

        BankSoal::create([
            'audio_paket_id'   => $paket->id,
            'kategori'         => 'listening',
            'tipe_paket'       => $paket->tipe_paket,
            'tipe_soal'        => 'multiple_choice',
            'pertanyaan'       => $request->pertanyaan,
            'pilihan_a'        => $request->pilihan_a,
            'pilihan_b'        => $request->pilihan_b,
            'pilihan_c'        => $request->pilihan_c,
            'pilihan_d'        => $request->pilihan_d,
            'jawaban_benar'    => $request->jawaban_benar,
            'start_second'     => (int) $request->start_second,
            'order_number'     => (int) $request->order_number,
            'nomor_soal'       => (int) $request->order_number,
            'audio_script'     => $request->audio_script,
            'tingkat_kesulitan'=> $request->tingkat_kesulitan ?? 'medium',
            'part'             => $request->part ?? 'A',
            'group_id'         => $request->group_id,
            'skill_materi'     => $request->skill_materi,
            'is_aktif'         => true,
            'created_by'       => auth()->id(),
        ]);

        // Update jumlah_soal di paket
        $paket->update([
            'jumlah_soal' => BankSoal::where('audio_paket_id', $paket->id)->count(),
        ]);

        return response()->json(['ok' => true, 'msg' => 'Soal berhasil disimpan.']);
    }

    /** Update durasi audio setelah JS load */
    public function updateDurasi(Request $request, $id)
    {
        if ($request->isJson()) $request->merge($request->json()->all());
        ListeningAudioPaket::findOrFail($id)->update([
            'durasi_detik' => (int) $request->durasi_detik,
        ]);
        return response()->json(['ok' => true]);
    }

    /** Hapus 1 soal */
    public function destroySoal($soalId)
    {
        $soal    = BankSoal::findOrFail($soalId);
        $paketId = $soal->audio_paket_id;
        $soal->delete();
        ListeningAudioPaket::where('id', $paketId)
            ->update(['jumlah_soal' => BankSoal::where('audio_paket_id', $paketId)->count()]);
        return response()->json(['ok' => true]);
    }

    /** Hapus paket beserta soalnya */
    public function destroy($id)
    {
        $paket = ListeningAudioPaket::findOrFail($id);
        // Hapus file audio
        if ($paket->audio_url) {
            \Storage::disk('public')->delete($paket->audio_url);
        }
        $paket->delete();
        return redirect()->route('admin.listening.index')
            ->with('success', 'Paket listening berhasil dihapus.');
    }
}
