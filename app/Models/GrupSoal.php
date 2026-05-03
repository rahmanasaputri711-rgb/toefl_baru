<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GrupSoal extends Model {
    protected $table    = 'grup_soal';
    protected $fillable = [
        'created_by','kategori','part','judul',
        'passage_teks','audio_url','durasi_audio_detik',
        'deskripsi','jumlah_soal','is_aktif',
    ];

    public function creator() { return $this->belongsTo(User::class,'created_by'); }
    public function soal()    { return $this->hasMany(BankSoal::class,'grup_soal_id'); }

    /** Rebuild cache jumlah_soal */
    public function syncCount(): void {
        $this->update(['jumlah_soal' => $this->soal()->count()]);
    }
}
