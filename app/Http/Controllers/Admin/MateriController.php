<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    public function index(Request $request)
    {
        $query = Materi::query();
        if ($request->filled('kategori')) $query->where('kategori',$request->kategori);
        $materi = $query->orderBy('urutan')->paginate(15)->withQueryString();
        return view('admin.materi.index', compact('materi'));
    }

    public function create() { return view('admin.materi.create'); }

    public function store(Request $request)
    {
        $request->validate([
            'judul'    => 'required|string|max:255',
            'kategori' => 'required|in:reading,listening,structure',
            'tipe_file'=> 'required|in:none,pdf,audio,video',
        ]);

        $data = $request->only(['judul','deskripsi','kategori','konten','tipe_file','estimasi_menit','urutan']);
        $data['created_by'] = auth()->id();
        $data['is_aktif']   = 1;

        if ($request->hasFile('file_upload')) {
            $data['file_url'] = $request->file('file_upload')->store('materi','public');
        }

        Materi::create($data);
        return redirect()->route('admin.materi.index')->with('success','Materi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        return view('admin.materi.edit', ['materi' => Materi::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $materi = Materi::findOrFail($id);
        $data   = $request->only(['judul','deskripsi','kategori','konten','tipe_file','estimasi_menit','urutan']);
        $data['is_aktif'] = $request->has('is_aktif') ? 1 : 0;

        if ($request->hasFile('file_upload')) {
            if ($materi->file_url) Storage::disk('public')->delete($materi->file_url);
            $data['file_url'] = $request->file('file_upload')->store('materi','public');
        }

        $materi->update($data);
        return redirect()->route('admin.materi.index')->with('success','Materi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $m = Materi::findOrFail($id);
        if ($m->file_url) Storage::disk('public')->delete($m->file_url);
        $m->delete();
        return back()->with('success','Materi dihapus.');
    }
}
