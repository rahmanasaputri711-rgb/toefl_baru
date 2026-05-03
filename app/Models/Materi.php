<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Materi extends Model {
    protected $table = 'materi';
    protected $fillable = ['created_by','kategori','judul','deskripsi','konten','file_url','tipe_file','estimasi_menit','urutan','is_aktif'];
}
