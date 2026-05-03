<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PercobaanTes extends Model {
    protected $table    = 'percobaan_tes';
    protected $fillable = [
        'user_id','sesi_id','tes_ke',
        'waktu_mulai','waktu_selesai','last_question',
        'skor_listening','skor_structure','skor_reading','skor_total',
        'jumlah_benar','jumlah_salah','jumlah_tidak_dijawab',
        'status','jumlah_pelanggaran','status_sanksi',
        'last_autosave_at','ip_address','browser_info',
    ];

    public function user()    { return $this->belongsTo(User::class); }
    public function sesiTes() { return $this->belongsTo(SesiTes::class,'sesi_id'); }
    public function jawaban() { return $this->hasMany(JawabanMahasiswa::class,'percobaan_id'); }

    /** Label urutan tes: "Percobaan ke-1", "Percobaan ke-2", dst */
    public function getTesKeLabelAttribute(): string {
        return 'Percobaan ke-' . ($this->tes_ke ?? 1);
    }
}
