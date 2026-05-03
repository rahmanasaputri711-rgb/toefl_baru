<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilTes extends Model
{
    protected $table = 'hasil_tes';

    protected $fillable = [
        'user_id',
        'skor',
        'jumlah_benar',
        'jumlah_soal'
    ];
}