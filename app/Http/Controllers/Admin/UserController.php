<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where(function($q) {
            $q->where('role', '!=', 'admin')->orWhereNull('role');
        })->with(['percobaan']);

        if ($request->filled('status'))
            $query->where('is_active', (bool)$request->status);

        if ($request->filled('search'))
            $query->where(function($q) use ($request) {
                $q->where('name',  'like', '%'.$request->search.'%')
                  ->orWhere('email','like', '%'.$request->search.'%');
            });

        $users = $query->latest()->paginate(20)->withQueryString();

        $totalUser    = User::where(fn($q) => $q->where('role','!=','admin')->orWhereNull('role'))->count();
        $totalAktif   = User::where(fn($q) => $q->where('role','!=','admin')->orWhereNull('role'))->where('is_active',1)->count();
        $totalNonaktif= $totalUser - $totalAktif;
        $totalBaru    = User::where(fn($q) => $q->where('role','!=','admin')->orWhereNull('role'))
            ->whereMonth('created_at', now()->month)->count();

        return view('admin.user.index', compact(
            'users','totalUser','totalAktif','totalNonaktif','totalBaru'
        ));
    }

    public function show($id)
    {
        $user        = User::findOrFail($id);
        $pendaftaran = \App\Models\PendaftaranTes::where('user_id', $id)
            ->with('sesiTes')->latest()->get();
        $percobaan   = \App\Models\PercobaanTes::where('user_id', $id)
            ->with('sesiTes')->latest()->get();
        return view('admin.user.show', compact('user','pendaftaran','percobaan'));
    }

    public function toggleAktif($id)
    {
        $user  = User::findOrFail($id);
        $aktif = !(bool)$user->is_active;
        $user->update(['is_active' => $aktif]);
        $status = $aktif ? 'diaktifkan' : 'dinonaktifkan';

        Notifikasi::create([
            'user_id' => $user->id,
            'judul'   => 'Status Akun Diperbarui',
            'pesan'   => "Akun Anda telah {$status} oleh admin UPA Bahasa.",
            'tipe'    => 'info',
        ]);

        return back()->with('success', "Akun {$user->name} berhasil {$status}.");
    }

    public function aktifkanDariPendaftaran($userId)
    {
        $user = User::findOrFail($userId);
        $user->update(['is_active' => true]);
        return back()->with('success', "Akun {$user->name} berhasil diaktifkan.");
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }
}
