<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SesiTes extends Model {
    protected $table = 'sesi_tes';
    protected $fillable = ['admin_id','judul','deskripsi','tipe_tes','durasi_menit',
        'jumlah_soal_reading','jumlah_soal_listening','jumlah_soal_structure',
        'kuota_peserta','peserta_terdaftar','khusus_tes_full','password_sesi',
        'waktu_mulai','waktu_selesai','tampilkan_hasil','tampilkan_pembahasan',
        'is_published','is_aktif'];
    protected $casts = ['waktu_mulai'=>'datetime','waktu_selesai'=>'datetime'];
    public function soal() { return $this->hasMany(SesiTesSoal::class,'sesi_id'); }
    public function pendaftaran() { return $this->hasMany(PendaftaranTes::class,'sesi_id'); }
}
