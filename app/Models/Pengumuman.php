<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Pengumuman extends Model {
    protected $table = 'pengumuman';
    protected $fillable = ['admin_id','judul','konten','target','is_published','is_pinned','published_at','expired_at'];
    protected $casts = ['published_at'=>'datetime','expired_at'=>'datetime'];
    public function admin() { return $this->belongsTo(User::class,'admin_id'); }
}
