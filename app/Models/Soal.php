<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    protected $fillable = [
        'passage_id',
        'nomor_soal',
        'pertanyaan',
        'opsi_a',
        'opsi_b',
        'opsi_c',
        'opsi_d',
        'jawaban',
    ];

    public function passage()
    {
        return $this->belongsTo(Passage::class);
    }
}