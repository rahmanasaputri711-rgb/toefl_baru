<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankSoal;
use Illuminate\Http\Request;

class StructureController extends Controller
{
    public function create()
    {
        $nextNomor = (BankSoal::where('kategori','structure')->max('nomor_soal') ?? 0) + 1;
        $soalList  = BankSoal::where('kategori','structure')
            ->orderBy('nomor_soal')->get();
        return view('admin.structure.create', compact('nextNomor','soalList'));
    }

    public function store(Request $request)
    {
        if (!$request->filled('pertanyaan'))
            return response()->json(['ok'=>false,'msg'=>'Kalimat soal tidak boleh kosong.']);
        if (!$request->filled('jawaban_benar'))
            return response()->json(['ok'=>false,'msg'=>'Pilih jawaban benar.']);

        BankSoal::create([
            'kategori'          => 'structure',
            'tipe_paket'        => 'full', // Bank Soal selalu full
            'tipe_soal'         => 'multiple_choice',
            'sub_bagian'        => $request->sub_bagian ?? 'completion',
            'nomor_soal'        => (int)($request->nomor_soal ?? 0),
            'pertanyaan'        => $request->pertanyaan
            'pilihan_a'         => $request->pilihan_a ?? '-',
            'pilihan_b'         => $request->pilihan_b ?? '-',
            'pilihan_c'         => $request->pilihan_c ?? '-',
            'pilihan_d'         => $request->pilihan_d ?? '-',
            'jawaban_benar'     => $request->jawaban_benar,
            'pembahasan'        => $request->pembahasan,
            'tingkat_kesulitan' => $request->tingkat_kesulitan ?? 'medium',
            'is_aktif'          => true,
            'created_by'        => auth()->id(),
        ]);

        return response()->json(['ok'=>true,'msg'=>'Soal No.'.$request->nomor_soal.' disimpan.']);
    }

    public function destroy($id)
    {
        BankSoal::where('kategori','structure')->findOrFail($id)->delete();
        return response()->json(['ok'=>true]);
    }
}