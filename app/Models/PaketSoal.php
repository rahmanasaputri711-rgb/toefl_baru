<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PaketSoal extends Model {
    protected $table    = 'paket_soal';
    protected $fillable = [
        'created_by','nama','deskripsi',
        'jumlah_listening','jumlah_structure','jumlah_reading',
        'status','is_aktif',
    ];

    // Standar TOEFL ITP
    public const TARGET = ['listening' => 50, 'structure' => 40, 'reading' => 50];

    public function creator() { return $this->belongsTo(User::class,'created_by'); }

    public function soal() {
        return $this->belongsToMany(BankSoal::class,'paket_soal_detail','paket_id','soal_id')
                    ->withPivot('urutan')->orderByPivot('urutan');
    }

    public function detail() {
        return $this->hasMany(PaketSoalDetail::class,'paket_id');
    }

    /** Hitung ulang jumlah per kategori + update status valid/invalid */
    public function validate(): void {
        $counts = $this->soal()
            ->select('kategori', \DB::raw('count(*) as total'))
            ->groupBy('kategori')
            ->pluck('total','kategori')
            ->toArray();

        $l = $counts['listening'] ?? 0;
        $s = $counts['structure'] ?? 0;
        $r = $counts['reading']   ?? 0;

        $valid = ($l === self::TARGET['listening'])
              && ($s === self::TARGET['structure'])
              && ($r === self::TARGET['reading']);

        $this->update([
            'jumlah_listening' => $l,
            'jumlah_structure' => $s,
            'jumlah_reading'   => $r,
            'status'           => ($l+$s+$r === 0) ? 'draft' : ($valid ? 'valid' : 'invalid'),
        ]);
    }
}
