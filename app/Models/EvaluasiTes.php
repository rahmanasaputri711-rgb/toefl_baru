<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class EvaluasiTes extends Model {
    protected $table = 'evaluasi_tes';
    protected $fillable = ['sesi_id','admin_id','judul','catatan','rekomendasi','untuk_user','is_published','published_at'];
    protected $casts = ['published_at' => 'datetime'];
    public function sesiTes() { return $this->belongsTo(SesiTes::class,'sesi_id'); }
    public function admin()   { return $this->belongsTo(User::class,'admin_id'); }
}
