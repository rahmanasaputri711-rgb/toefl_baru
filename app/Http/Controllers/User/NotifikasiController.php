<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = Notifikasi::where('user_id', auth()->id())
            ->orderByDesc('is_important')
            ->latest()
            ->paginate(20);
        return view('user.notifikasi.index', compact('notifikasi'));
    }

    public function baca($id)
    {
        Notifikasi::where('id', $id)->where('user_id', auth()->id())
            ->update(['is_read' => true, 'read_at' => now()]);
        return back();
    }

    public function bacaSemua()
    {
        Notifikasi::where('user_id', auth()->id())->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
        return back()->with('success', 'Semua notifikasi ditandai dibaca.');
    }
}
