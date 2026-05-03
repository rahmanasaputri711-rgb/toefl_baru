<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class JawabanMahasiswa extends Model {
    protected $table = 'jawaban_mahasiswa';
    protected $fillable = ['percobaan_id','soal_id','jawaban_dipilih','is_benar',
        'status_soal','is_synced','nomor_soal','waktu_dijawab','durasi_detik'];
    protected $casts = ['waktu_dijawab'=>'datetime'];
    public function soal()      { return $this->belongsTo(BankSoal::class,'soal_id'); }
    public function percobaan() { return $this->belongsTo(PercobaanTes::class,'percobaan_id'); }
}
