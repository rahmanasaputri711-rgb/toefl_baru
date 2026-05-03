<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LogPendaftaran extends Model {
    protected $table    = 'log_pendaftaran';
    protected $fillable = ['pendaftaran_id','status_lama','status_baru','diubah_oleh','keterangan'];
    public function pendaftaran() { return $this->belongsTo(PendaftaranTes::class,'pendaftaran_id'); }
    public function admin()       { return $this->belongsTo(User::class,'diubah_oleh'); }
}
