<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GrupSoal;
use App\Models\BankSoal;
use App\Services\AudioService;
use Illuminate\Http\Request;

class GrupSoalController extends Controller
{
    public function index(Request $request)
    {
        $q = GrupSoal::with('creator')->withCount('soal');
        if ($request->filled('kategori')) $q->where('kategori', $request->kategori);
        if ($request->filled('search'))   $q->where('judul','like','%'.$request->search.'%');
        $grups = $q->latest()->paginate(15)->withQueryString();
        return view('admin.grup_soal.index', compact('grups'));
    }

    public function create()  { return view('admin.grup_soal.create'); }

    public function store(Request $request)
    {
        $request->validate([
            'kategori'   => 'required|in:listening,reading',
            'part'       => 'nullable|in:A,B,C',
            'judul'      => 'nullable|string|max:255',
            'audio_file' => 'nullable|file|mimes:mp3,ogg,wav,m4a,mpeg,webm|max:20480',
        ]);

        $data = $request->only(['kategori','part','judul','passage_teks','deskripsi']);
        $data['created_by'] = auth()->id();
        $data['is_aktif']   = true;

        if ($request->hasFile('audio_file') && $request->file('audio_file')->isValid()) {
            $data['audio_url'] = $request->file('audio_file')->store('audio','public');
        }

        $grup = GrupSoal::create($data);

        return redirect()->route('admin.grup.show', $grup->id)
            ->with('success', 'Grup dibuat. Silakan tambahkan soal ke dalamnya.');
    }

    public function show($id)
    {
        $grup = GrupSoal::with(['soal' => fn($q) => $q->orderBy('nomor_soal')])->findOrFail($id);
        return view('admin.grup_soal.show', compact('grup'));
    }

    public function edit($id)
    {
        $grup = GrupSoal::findOrFail($id);
        return view('admin.grup_soal.edit', compact('grup'));
    }

    public function update(Request $request, $id)
    {
        $grup = GrupSoal::findOrFail($id);
        $request->validate([
            'kategori'   => 'required|in:listening,reading',
            'part'       => 'nullable|in:A,B,C',
            'audio_file' => 'nullable|file|mimes:mp3,ogg,wav,m4a,mpeg,webm|max:20480',
        ]);

        $data = $request->only(['kategori','part','judul','passage_teks','deskripsi']);

        if ($request->boolean('hapus_audio') && $grup->audio_url) {
            AudioService::delete($grup->audio_url);
            $data['audio_url'] = null;
        }
        if ($request->hasFile('audio_file') && $request->file('audio_file')->isValid()) {
            if ($grup->audio_url) AudioService::delete($grup->audio_url);
            $data['audio_url'] = $request->file('audio_file')->store('audio','public');
        }

        $grup->update($data);
        return redirect()->route('admin.grup.show', $grup->id)->with('success','Grup diperbarui.');
    }

    public function destroy($id)
    {
        $grup = GrupSoal::findOrFail($id);
        if ($grup->audio_url) AudioService::delete($grup->audio_url);
        // Soal yang terkait: lepas relasi (bukan hapus soal)
        BankSoal::where('grup_soal_id', $id)->update(['grup_soal_id' => null]);
        $grup->delete();
        return redirect()->route('admin.grup.index')->with('success','Grup dihapus.');
    }
}
