<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SesiTesSoal extends Model {
    protected $table = 'sesi_tes_soal';
    protected $fillable = ['sesi_id','soal_id','urutan_acak','bagian'];
    public function soal() { return $this->belongsTo(BankSoal::class,'soal_id'); }
}
