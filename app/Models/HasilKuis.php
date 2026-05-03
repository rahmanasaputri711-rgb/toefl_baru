<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilKuis extends Model
{
    protected $table = 'hasil_kuis';

protected $fillable = [
    'user_id',
    'materi_id',
    'skor',
    'jumlah_benar',
    'jumlah_soal',
    'durasi_detik',
];
}
