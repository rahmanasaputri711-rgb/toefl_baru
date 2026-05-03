<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PercobaanTes extends Model {
    protected $table    = 'percobaan_tes';
    protected $fillable = [
        'user_id','sesi_id','tes_ke',
        'waktu_mulai','waktu_berakhir','waktu_selesai','last_question',
        'skor_listening','skor_structure','skor_reading','skor_total',
        'jumlah_benar','jumlah_salah','jumlah_tidak_dijawab',
        'status','jumlah_pelanggaran','status_sanksi','status_curang','reset_count',
        'last_autosave_at','ip_address','browser_info',
    ];
    protected $casts = [
        'waktu_mulai'     => 'datetime',
        'waktu_berakhir'  => 'datetime',
        'waktu_selesai'   => 'datetime',
        'status_curang'   => 'boolean',
        'last_autosave_at'=> 'datetime',
    ];

    public function user()        { return $this->belongsTo(User::class); }
    public function sesiTes()     { return $this->belongsTo(SesiTes::class,'sesi_id'); }
    public function jawaban()     { return $this->hasMany(JawabanMahasiswa::class,'percobaan_id'); }
    public function pelanggaran() { return $this->hasMany(PelanggaranTes::class,'percobaan_id'); }
    public function urutanSoal()  { return $this->hasMany(UrutanSoalSesi::class,'percobaan_id'); }

    /** Apakah waktu tes sudah habis? (server-side check) */
    public function waktuHabis(): bool {
        return $this->waktu_berakhir && now()->gt($this->waktu_berakhir);
    }

    public function getTesKeLabelAttribute(): string {
        return 'Percobaan ke-' . ($this->tes_ke ?? 1);
    }
}
