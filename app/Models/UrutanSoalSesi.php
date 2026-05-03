<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UrutanSoalSesi extends Model {
    protected $table    = 'urutan_soal_sesi';
    protected $fillable = ['percobaan_id','soal_id','section','urutan'];
    public function soal()      { return $this->belongsTo(BankSoal::class,'soal_id'); }
    public function percobaan() { return $this->belongsTo(PercobaanTes::class,'percobaan_id'); }
}
