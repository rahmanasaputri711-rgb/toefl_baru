<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KuisMateri;

class KuisMateriController extends Controller
{
    // CREATE
    public function store(Request $request, $materi_id)
    {
        $request->validate([
            'pertanyaan' => 'required',
            'pilihan_a' => 'required',
            'pilihan_b' => 'required',
            'pilihan_c' => 'required',
            'pilihan_d' => 'required',
            'jawaban_benar' => 'required|in:a,b,c,d',
        ]);

        $kuis = KuisMateri::create([
            'materi_id' => $materi_id,
            'pertanyaan' => $request->pertanyaan,
            'pilihan_a' => $request->pilihan_a,
            'pilihan_b' => $request->pilihan_b,
            'pilihan_c' => $request->pilihan_c,
            'pilihan_d' => $request->pilihan_d,
            'jawaban_benar' => $request->jawaban_benar,
            'penjelasan' => $request->penjelasan,
            'urutan' => $request->urutan ?? 1,
        ]);

        return response()->json($kuis);
    }

    // READ BY MATERI
    public function index($materi_id)
    {
        return KuisMateri::where('materi_id', $materi_id)
            ->orderBy('urutan')
            ->get();
    }
}