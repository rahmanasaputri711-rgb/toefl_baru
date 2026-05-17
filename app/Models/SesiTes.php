<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SesiTes extends Model
{
    protected $table = 'sesi_tes';

    protected $fillable = [
        'admin_id','judul','deskripsi','tipe_tes','durasi_menit',
        'jumlah_soal_reading','jumlah_soal_listening','jumlah_soal_structure',
        'kuota_peserta','peserta_terdaftar','khusus_tes_full','password_sesi',
        'waktu_mulai','waktu_selesai','tampilkan_hasil','tampilkan_pembahasan',
        'is_published','is_aktif',
    ];

    protected $casts = [
        'waktu_mulai'  => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    // ── Relasi ────────────────────────────────────────────────────────
    public function soal()        { return $this->hasMany(SesiTesSoal::class, 'sesi_id'); }
    public function pendaftaran() { return $this->hasMany(PendaftaranTes::class, 'sesi_id'); }

    // ── Scopes ────────────────────────────────────────────────────────
    // Sesi hanya untuk Tes Full (pendaftaran resmi)
    public function scopeFull($q)  { return $q->where('tipe_tes', 'full'); }
    public function scopeAktif($q) { return $q->where('is_aktif', 1); }
}