<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BankSoal extends Model {
    protected $table    = 'bank_soal';
    protected $fillable = [
        'created_by','kategori','part','nomor_soal','tingkat_kesulitan',
        'untuk_tes_full','pertanyaan','group_id','grup_soal_id',
        'passage_teks','audio_url','durasi_audio_detik',
        'pilihan_a','pilihan_b','pilihan_c','pilihan_d',
        'jawaban_benar','pembahasan','bobot_nilai','is_aktif','pakai_count',
    ];

    // Part yang valid per kategori
    public static array $partMap = [
        'listening' => ['A'=>'Part A','B'=>'Part B','C'=>'Part C'],
        'structure' => ['A'=>'Part A (Structure)','B'=>'Part B (Written Expression)'],
        'reading'   => [],  // reading pakai passage/grup
    ];

    public function creator()  { return $this->belongsTo(User::class,'created_by'); }
    public function grupSoal() { return $this->belongsTo(GrupSoal::class,'grup_soal_id'); }

    public function getPartLabelAttribute(): string {
        if (!$this->part) return '—';
        return self::$partMap[$this->kategori][$this->part] ?? "Part {$this->part}";
    }
}
