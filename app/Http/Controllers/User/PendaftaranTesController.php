<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranTes;
use App\Models\LogPendaftaran;
use App\Models\SesiTes;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PendaftaranTesController extends Controller
{
    /** Halaman status pendaftaran user */
    public function status()
    {
        $userId = auth()->id();
        $user   = auth()->user();

        $aktif = PendaftaranTes::with('sesiTes')
            ->where('user_id', $userId)
            ->whereIn('status_pendaftaran', ['menunggu','dikonfirmasi'])
            ->latest()->first();

        $riwayat = PendaftaranTes::with('sesiTes')
            ->where('user_id', $userId)
            ->whereNotIn('status_pendaftaran', ['menunggu','dikonfirmasi'])
            ->latest()->get();

        return view('user.pendaftaran.status', compact('aktif','riwayat','user'));
    }

    /** Submit pendaftaran tes full */
    public function daftar(Request $request)
    {
        $request->validate([
            'sesi_id'          => 'required|exists:sesi_tes,id',
            'nim_nip'          => 'required|string|max:50',
            'status_polman'    => 'required|in:mahasiswa,dosen,staf,alumni',
            'program_studi'    => 'required|string|max:100',
            'no_telepon'       => 'required|string|max:20',
            'berkas_identitas' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $userId = auth()->id();
        $user   = auth()->user();

        // Cek sudah lulus
        $sudahLulus = \App\Models\PercobaanTes::where('user_id', $userId)
            ->where('status','selesai')
            ->whereHas('sesiTes', fn($q) => $q->where('tipe_tes','full'))
            ->where('skor_total', '>=', 500)->exists();
        if ($sudahLulus)
            return back()->with('error', 'Kamu sudah lulus tes TOEFL ITP dan tidak perlu mendaftar lagi.');

        // Cek maksimal 3× tes
        $jumlahTes = \App\Models\PercobaanTes::where('user_id', $userId)
            ->where('status','selesai')
            ->whereHas('sesiTes', fn($q) => $q->where('tipe_tes','full'))
            ->count();
        if ($jumlahTes >= 3)
            return back()->with('error', 'Kamu sudah mencapai batas maksimal 3 kali tes full. Hubungi UPA Bahasa.');

        // Cek blokir absen
        if ($user->diblokir()) {
            return back()->with('error',
                'Akunmu dibekukan sementara karena 3x tidak hadir. Hubungi UPA Bahasa.');
        }

        // Cek 1 pendaftaran aktif per user
        $punya = PendaftaranTes::where('user_id', $userId)
            ->whereIn('status_pendaftaran', ['menunggu','dikonfirmasi'])->exists();
        if ($punya) {
            return back()->with('error',
                'Kamu sudah memiliki pendaftaran aktif. Selesaikan atau batalkan dulu sebelum mendaftar jadwal lain.');
        }

        // Cek kuota
        $sesi = SesiTes::findOrFail($request->sesi_id);
        if ($sesi->peserta_terdaftar >= $sesi->kuota_peserta)
            return back()->with('error', 'Kuota sesi ini sudah penuh.');

        $path = $request->file('berkas_identitas')->store('ktm', 'public');

        $p = PendaftaranTes::create([
            'user_id'              => $userId,
            'sesi_id'              => $request->sesi_id,
            'nim_nip'              => $request->nim_nip,
            'status_polman'        => $request->status_polman,
            'program_studi'        => $request->program_studi,
            'no_telepon'           => $request->no_telepon,
            'berkas_identitas_url' => $path,
            'status_pendaftaran'   => 'menunggu',
        ]);

        // Log
        LogPendaftaran::create([
            'pendaftaran_id' => $p->id,
            'status_lama'    => null,
            'status_baru'    => 'menunggu',
            'diubah_oleh'    => $userId,
            'keterangan'     => 'Pendaftaran disubmit oleh user',
        ]);

        Notifikasi::create([
            'user_id' => $userId,
            'judul'   => '📋 Pendaftaran Dikirim',
            'pesan'   => 'Pendaftaran Tes Full kamu sedang ditinjau admin UPA Bahasa.',
            'tipe'    => 'info',
        ]);

        return redirect()->route('user.pendaftaran.status')
            ->with('success', 'Pendaftaran berhasil dikirim. Tunggu konfirmasi admin.');
    }

    /** Batal pendaftaran oleh user */
    public function batal(Request $request, $id)
    {
        $userId = auth()->id();
        $p      = PendaftaranTes::where('id', $id)->where('user_id', $userId)->firstOrFail();

        if (!$p->bisaDibatalkanUser()) {
            return back()->with('error',
                'Pendaftaran tidak bisa dibatalkan. Hanya bisa dibatalkan saat status menunggu dan minimal H-2 sebelum tes.');
        }

        $p->update([
            'status_pendaftaran' => 'dibatalkan',
            'dibatalkan_at'      => now(),
            'alasan_batal'       => $request->alasan ?? 'Dibatalkan oleh user',
        ]);

        // Kembalikan kuota
        $p->sesiTes?->decrement('peserta_terdaftar');

        LogPendaftaran::create([
            'pendaftaran_id' => $p->id,
            'status_lama'    => 'menunggu',
            'status_baru'    => 'dibatalkan',
            'diubah_oleh'    => $userId,
            'keterangan'     => 'Dibatalkan oleh user: ' . ($request->alasan ?? '-'),
        ]);

        return redirect()->route('user.pendaftaran.status')
            ->with('success', 'Pendaftaran berhasil dibatalkan. Kuota telah dikembalikan.');
    }
}
