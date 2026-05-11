<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Passage extends Model {
    protected $table    = 'passages';
    protected $fillable = ['created_by','modul_id','paket_id','grup_id',
        'judul','teks','image_url','tipe_paket','is_aktif','urutan'];
    protected $casts = ['is_aktif'=>'boolean'];

    public function soal()    { return $this->hasMany(BankSoal::class,'passage_id')->orderBy('nomor_dalam_paket'); }
    public function modul()   { return $this->belongsTo(ModulSoal::class,'modul_id'); }
    public function creator() { return $this->belongsTo(User::class,'created_by'); }

    // Render teks dengan highlight [bold] dan nomor baris
    public function renderTeks(): string {
        $teks = htmlspecialchars($this->teks);
        // **bold** syntax
        $teks = preg_replace('/\*\*(.+?)\*\*/s', '<strong style="color:#fbbf24">$1</strong>', $teks);
        return nl2br($teks);
    }
}
