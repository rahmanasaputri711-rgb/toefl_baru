<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuisMateri extends Model
{
    protected $table = 'kuis_materi';

    protected $fillable = [
        'materi_id',
        'pertanyaan',
        'pilihan_a',
        'pilihan_b',
        'pilihan_c',
        'pilihan_d',
        'jawaban_benar',
        'penjelasan',
        'urutan',
    ];
}