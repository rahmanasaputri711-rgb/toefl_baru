<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PaketSoalDetail extends Model {
    protected $table    = 'paket_soal_detail';
    protected $fillable = ['paket_id','soal_id','urutan'];

    public function soal()  { return $this->belongsTo(BankSoal::class,'soal_id'); }
    public function paket() { return $this->belongsTo(PaketSoal::class,'paket_id'); }
}
