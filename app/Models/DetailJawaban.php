<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailJawaban extends Model
{
    protected $table = 'detail_jawaban'; // ← INI WAJIB

    protected $fillable = [
        'hasil_tes_id',
        'soal_id',
        'jawaban_user',
        'jawaban_benar',
        'is_benar'
    ];

    public function soal()
    {
        return $this->belongsTo(\App\Models\BankSoal::class, 'soal_id');
    }

}
