<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\Materi;
use Illuminate\Http\Request;

class MateriController extends Controller
{
    public function index(Request $request)
    {
        $query = Materi::where('is_aktif',1)->orderBy('urutan');
        if ($request->filled('kategori')) $query->where('kategori',$request->kategori);
        $materi = $query->get();
        return view('user.materi.index', compact('materi'));
    }

    public function show($id)
    {
        $materi   = Materi::where('is_aktif',1)->findOrFail($id);
        $materiLain = Materi::where('is_aktif',1)->where('id','!=',$id)
            ->where('kategori',$materi->kategori)->take(5)->get();
        return view('user.materi.show', compact('materi','materiLain'));
    }
}
