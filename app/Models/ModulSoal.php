<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ModulSoal extends Model
{
    protected $table    = 'modul_soal';
    protected $fillable = [
        'grup_id','paket_id','created_by',
        'tipe_modul','judul','audio_paket_id',
        'nomor_soal_mulai','nomor_soal_selesai','urutan','is_selesai',
    ];
    protected $casts = ['is_selesai' => 'boolean'];

    // ── Konstanta tipe modul ──────────────────────────────────────
    public const TIPE = [
        // Reading
        'passage'         => ['label'=>'📄 Passage',        'color'=>'#3b82f6', 'grup'=>'reading'],
        'missing_letters' => ['label'=>'🔤 Missing Letters','color'=>'#10b981', 'grup'=>'reading'],
        'image_email'     => ['label'=>'📧 Gambar / Email', 'color'=>'#f59e0b', 'grup'=>'reading'],
        // Listening — semua pakai 1 audio full, modul hanya untuk pengelompokan admin
        'conversation'    => ['label'=>'💬 Conversation',   'color'=>'#fb923c', 'grup'=>'listening'],
        'lecture'         => ['label'=>'🎓 Lecture',        'color'=>'#f97316', 'grup'=>'listening'],
        'discussion'      => ['label'=>'🗣 Discussion',     'color'=>'#ea580c', 'grup'=>'listening'],
        'short_talk'      => ['label'=>'⚡ Short Talk',     'color'=>'#dc2626', 'grup'=>'listening'],
    ];

    public const TIPE_LISTENING = ['conversation','lecture','discussion','short_talk'];

    // ── Helper ────────────────────────────────────────────────────
    public function isListening(): bool {
        return in_array($this->tipe_modul, self::TIPE_LISTENING);
    }

    public function getRentangAttribute(): string {
        $a = $this->nomor_soal_mulai;
        $b = $this->nomor_soal_selesai;
        return $a === $b ? "No.$a" : "No.$a–$b";
    }

    public function getJumlahTargetAttribute(): int {
        return $this->nomor_soal_selesai - $this->nomor_soal_mulai + 1;
    }

    public function getTipeLabelAttribute(): string {
        return self::TIPE[$this->tipe_modul]['label'] ?? $this->tipe_modul;
    }

    public function getTipeColorAttribute(): string {
        return self::TIPE[$this->tipe_modul]['color'] ?? '#888';
    }

    // ── Relasi ────────────────────────────────────────────────────
    public function paket()      { return $this->belongsTo(PaketSoal::class, 'paket_id'); }
    public function grup()       { return $this->belongsTo(GrupSoal::class,  'grup_id'); }
    public function creator()    { return $this->belongsTo(User::class, 'created_by'); }
    public function passages()   { return $this->hasMany(Passage::class, 'modul_id'); }
    public function audioPaket() { return $this->belongsTo(ListeningAudioPaket::class, 'audio_paket_id'); }
    public function soal()       {
        return $this->hasMany(BankSoal::class, 'modul_id')->orderBy('nomor_dalam_paket');
    }
}
