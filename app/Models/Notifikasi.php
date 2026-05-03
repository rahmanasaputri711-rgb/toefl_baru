<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Notifikasi extends Model {
    protected $table = 'notifikasi';
    protected $fillable = ['user_id','judul','pesan','tipe','referensi_id','referensi_tipe','is_important','is_read','read_at'];
    public function user() { return $this->belongsTo(User::class); }
}
