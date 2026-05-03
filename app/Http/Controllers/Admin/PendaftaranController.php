<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranTes;
use App\Models\LogPendaftaran;
use App\Models\LogAdmin;
use App\Models\Notifikasi;
use App\Models\SesiTes;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    public function __construct(private NotificationService $notif) {}

    public function index(Request $request)
    {
        $query = PendaftaranTes::with(['user','sesiTes']);

        if ($request->filled('status'))
            $query->where('status_pendaftaran', $request->status);
        if ($request->filled('sesi_id'))
            $query->where('sesi_id', $request->sesi_id);
        if ($request->filled('search'))
            $query->where(function($q) use ($request) {
                $q->where('nomor_pendaftaran','like','%'.$request->search.'%')
                  ->orWhereHas('user', fn($u) =>
                    $u->where('name','like','%'.$request->search.'%')
                      ->orWhere('email','like','%'.$request->search.'%'));
            });

        $pendaftaran     = $query->latest()->paginate(15)->withQueryString();
        $sesiList        = SesiTes::where('tipe_tes','full')->orderBy('waktu_mulai','desc')->get();
        $statMenunggu    = PendaftaranTes::where('status_pendaftaran','menunggu')->count();
        $statDikonfirmasi= PendaftaranTes::where('status_pendaftaran','dikonfirmasi')->count();
        $statDitolak     = PendaftaranTes::where('status_pendaftaran','ditolak')->count();
        $statDibatalkan  = PendaftaranTes::where('status_pendaftaran','dibatalkan')->count();

        return view('admin.pendaftaran.index', compact(
            'pendaftaran','sesiList',
            'statMenunggu','statDikonfirmasi','statDitolak','statDibatalkan'
        ));
    }

    public function show($id)
    {
        $p = PendaftaranTes::with(['user','sesiTes','logs.admin'])->findOrFail($id);
        return view('admin.pendaftaran.show', compact('p'));
    }

    public function konfirmasi($id)
    {
        $p = PendaftaranTes::with(['user','sesiTes'])->findOrFail($id);
        $nomor = 'TF-' . date('Y') . '-' . str_pad($p->id, 4, '0', STR_PAD_LEFT);

        $p->update([
            'status_pendaftaran' => 'dikonfirmasi',
            'dikonfirmasi_oleh'  => auth()->id(),
            'confirmed_at'       => now(),
            'nomor_pendaftaran'  => $nomor,
        ]);

        // Aktifkan akun user otomatis saat di-ACC
        User::where('id', $p->user_id)->update([
            'is_active' => true,
            'role'      => \DB::raw("COALESCE(NULLIF(role,''), 'user')"),
        ]);

        $p->sesiTes?->increment('peserta_terdaftar');

        LogPendaftaran::create([
            'pendaftaran_id' => $p->id,
            'status_lama'    => 'menunggu',
            'status_baru'    => 'dikonfirmasi',
            'diubah_oleh'    => auth()->id(),
            'keterangan'     => 'Dikonfirmasi oleh admin',
        ]);

        $jadwal = $p->sesiTes
            ? \Carbon\Carbon::parse($p->sesiTes->waktu_mulai)->format('d M Y, H:i')
            : '-';

        $this->notif->kirimNotifikasi(
            $p->user,
            '✅ Pendaftaran Tes Diterima',
            "Pendaftaranmu untuk \"{$p->sesiTes?->judul}\" dikonfirmasi. Nomor: {$nomor}. Jadwal: {$jadwal}.",
            'sukses', '/pendaftaran/status', true
        );

        try {
            \Mail::to($p->user->email)->send(new \App\Mail\KonfirmasiPendaftaran($p));
        } catch (\Exception $e) {
            \Log::error('Mail gagal: ' . $e->getMessage());
        }

        return back()->with('success', "ACC berhasil. Nomor: {$nomor}. Akun user diaktifkan.");
    }

    public function tolak(Request $request, $id)
    {
        $request->validate(['catatan_admin' => 'required|string|min:5']);
        $p = PendaftaranTes::with(['user','sesiTes'])->findOrFail($id);

        $p->update([
            'status_pendaftaran' => 'ditolak',
            'catatan_admin'      => $request->catatan_admin,
        ]);

        LogPendaftaran::create([
            'pendaftaran_id' => $p->id,
            'status_lama'    => 'menunggu',
            'status_baru'    => 'ditolak',
            'diubah_oleh'    => auth()->id(),
            'keterangan'     => $request->catatan_admin,
        ]);

        $this->notif->kirimNotifikasi(
            $p->user,
            '❌ Pendaftaran Tes Ditolak',
            "Pendaftaranmu ditolak. Alasan: {$request->catatan_admin}.",
            'danger', '/pendaftaran/status'
        );

        // Kirim EMAIL penolakan
        try {
            \Mail::to($p->user->email)->send(new \App\Mail\TolakPendaftaran($p));
        } catch (\Exception $e) {
            \Log::error('Email tolak gagal: ' . $e->getMessage());
        }

        return back()->with('success', 'Pendaftaran ditolak. Email notifikasi terkirim.');
    }

    /** Tandai user ABSEN (tidak hadir hari tes) */
    public function tandaiAbsen($id)
    {
        $p = PendaftaranTes::with('user')->findOrFail($id);
        $p->update(['is_hadir' => false, 'ditandai_absen_at' => now()]);
        $p->user?->increment('jumlah_absen');

        LogPendaftaran::create([
            'pendaftaran_id' => $p->id,
            'status_lama'    => $p->status_pendaftaran,
            'status_baru'    => 'absen',
            'diubah_oleh'    => auth()->id(),
            'keterangan'     => 'Ditandai tidak hadir oleh admin',
        ]);

        LogAdmin::create([
            'admin_id'    => auth()->id(),
            'aksi'        => 'tandai_absen',
            'target_type' => 'PendaftaranTes',
            'target_id'   => $p->id,
            'keterangan'  => "User {$p->user?->name} ditandai absen",
            'ip_address'  => request()->ip(),
        ]);

        if ($p->user?->diblokir()) {
            $this->notif->kirimNotifikasi(
                $p->user,
                '🚫 Akun Dibekukan',
                '3× tidak hadir. Hubungi UPA Bahasa.',
                'danger', '/dashboard', true
            );
        }

        return back()->with('success', 'User ditandai absen.');
    }
}
