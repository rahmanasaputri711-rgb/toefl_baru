<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PelanggaranTes extends Model {
    protected $table    = 'pelanggaran_tes';
    protected $fillable = [
        'percobaan_id','user_id','jenis','pelanggaran_ke',
        'keterangan','ip_address','waktu_pelanggaran',
    ];
    protected $casts = ['waktu_pelanggaran' => 'datetime'];
    public function percobaan() { return $this->belongsTo(PercobaanTes::class,'percobaan_id'); }
    public function user()      { return $this->belongsTo(User::class); }
}
