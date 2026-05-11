<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GrupSoal extends Model {
    protected $table    = 'grup_soal';
    protected $fillable = ['created_by','paket_id','kategori','part','judul',
        'passage_teks','audio_url','durasi_audio_detik','deskripsi','jumlah_soal','is_aktif'];

    public const KATEGORI = [
        'reading'   => ['label'=>'📖 Reading',   'color'=>'#10b981'],
        'listening' => ['label'=>'🎧 Listening', 'color'=>'#fb923c'],
        'structure' => ['label'=>'✏️ Structure', 'color'=>'#f59e0b'],
    ];

    public function paket()   { return $this->belongsTo(PaketSoal::class,'paket_id'); }
    public function creator() { return $this->belongsTo(User::class,'created_by'); }
    public function soal()    { return $this->hasMany(BankSoal::class,'grup_soal_id'); }
    public function modul()   { return $this->hasMany(ModulSoal::class,'grup_id')->orderBy('nomor_soal_mulai'); }
}
