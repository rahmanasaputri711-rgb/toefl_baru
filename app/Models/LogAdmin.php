<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LogAdmin extends Model {
    protected $table    = 'log_admin';
    protected $fillable = ['admin_id','aksi','target_type','target_id','keterangan','ip_address'];
    public function admin() { return $this->belongsTo(User::class,'admin_id'); }
}
