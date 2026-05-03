<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumuman = Pengumuman::latest()->paginate(10);
        return view('admin.pengumuman.index', compact('pengumuman'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'  => 'required|string|max:255',
            'konten' => 'required|string',
            'target' => 'required|in:semua,user',
        ]);

        Pengumuman::create([
            'admin_id'    => auth()->id(),
            'judul'       => $request->judul,
            'konten'      => $request->konten,
            'target'      => $request->target,
            'is_pinned'   => $request->has('is_pinned') ? 1 : 0,
            'is_published'=> $request->has('is_published') ? 1 : 0,
            'published_at'=> $request->has('is_published') ? now() : null,
            'expired_at'  => $request->expired_at ?: null,
        ]);

        return redirect()->route('admin.pengumuman.index')->with('success','Pengumuman berhasil dibuat.');
    }

    public function destroy($id)
    {
        Pengumuman::findOrFail($id)->delete();
        return back()->with('success','Pengumuman dihapus.');
    }

    public function togglePublish($id)
    {
        $p = Pengumuman::findOrFail($id);
        $p->update([
            'is_published' => !$p->is_published,
            'published_at' => !$p->is_published ? now() : null,
        ]);
        return back()->with('success','Status pengumuman diperbarui.');
    }
}
