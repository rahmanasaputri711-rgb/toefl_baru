<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AktivitasLog extends Model {
    protected $table = 'aktivitas_log';
    protected $fillable = ['user_id','percobaan_id','tipe_aksi','detail','pelanggaran_ke','ip_address'];
    public function user() { return $this->belongsTo(User::class); }
    public function percobaan() { return $this->belongsTo(PercobaanTes::class,'percobaan_id'); }
}
