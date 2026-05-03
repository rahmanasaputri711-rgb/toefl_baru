<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrafikProgress extends Model
{
    protected $table = 'grafik_progress';

protected $fillable = [
    'user_id',
    'sumber',
    'sumber_id',
    'tanggal',
    'skor_listening',
    'skor_structure',
    'skor_reading',
    'skor_toefl_estimasi',
];
}
