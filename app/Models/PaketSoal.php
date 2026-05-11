<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PaketSoal extends Model {
    protected $table    = 'paket_soal';
    protected $fillable = ['created_by','nama','deskripsi',
        'jumlah_listening','jumlah_structure','jumlah_reading','status','is_aktif'];

    public const TARGET = ['listening'=>50,'structure'=>40,'reading'=>50];

    public function creator() { return $this->belongsTo(User::class,'created_by'); }
    public function grupList(){ return $this->hasMany(GrupSoal::class,'paket_id')->orderBy('kategori'); }
    public function soal()    {
        return $this->belongsToMany(BankSoal::class,'paket_soal_detail','paket_id','soal_id')
                    ->withPivot('urutan')->orderByPivot('urutan');
    }
    public function detail()  { return $this->hasMany(PaketSoalDetail::class,'paket_id'); }

    // Semua soal bank_soal yang masuk paket ini langsung
    public function bankSoal() {
        return $this->hasMany(BankSoal::class,'paket_id')->orderBy('nomor_dalam_paket');
    }

    public function totalSoal(): int {
        return BankSoal::where('paket_id',$this->id)->count();
    }
}
