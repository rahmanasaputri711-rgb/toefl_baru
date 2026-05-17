<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BankSoal extends Model
{
    protected $table    = 'bank_soal';
    protected $fillable = [
        'created_by', 'kategori', 'part', 'sub_bagian', 'nomor_soal', 'urutan_soal',
        'tingkat_kesulitan', 'tipe_paket', 'skill_materi', 'untuk_tes_full',

        // Tipe soal (semua section)
        'tipe_soal',

        // Reading
        'passage_id', 'highlight_kata', 'highlight_paragraf',
        'insert_sentence_teks', 'fill_text', 'email_meta',

        // Listening
        'modul_id', 'paket_id', 'nomor_dalam_paket',
        'audio_paket_id', 'audio_url', 'audio_script', 'script_audio',
        'durasi_audio_detik', 'start_second', 'audio_end', 'pause_duration',
        'session_resume_time', 'order_number', 'image_url',

        // Structure
        'arrange_words',

        // Konten soal
        'group_id', 'grup_soal_id', 'pertanyaan', 'passage_teks',
        'pilihan_a', 'pilihan_b', 'pilihan_c', 'pilihan_d', 'pilihan_e', 'pilihan_f',
        'jawaban_benar', 'jawaban_benar_multiple',
        'pembahasan', 'rationale', 'bobot_nilai', 'is_aktif', 'pakai_count',
    ];

    protected $casts = [
        'is_aktif'       => 'boolean',
        'arrange_words'  => 'array',
        'email_meta'     => 'array',
    ];

    // ── Konstanta ──────────────────────────────────────────────────

    /** Tipe soal per section */
    public const TIPE_PER_SECTION = [
        'reading'   => [
            'academic_passage'    => '📄 Academic Passage',
            'email_reading'       => '📧 Email Reading',
            'fill_missing_letters'=> '🔤 Fill Missing Letters',
            'vocabulary'          => '🔵 Vocabulary in Context',
            'insert_sentence'     => '🟢 Insert a Sentence',
            'click_sentence'      => '🟣 Click on Sentence',
            'prose_summary'       => '🔴 Prose Summary',
        ],
        'listening' => [
            'best_response'  => '🎧 Best Response (Choose Answer)',
            'multiple_choice'=> '🎧 Multiple Choice',
        ],
        'structure' => [
            'best_response'    => '💬 Best Response (Dialogue)',
            'arrange_sentence' => '🔀 Arrange Sentence',
        ],
    ];

    public const TIPE_PAKET = [
        'full' => '🏆 Tes Full',
        // Simulasi, Mini, Praktik TIDAK menggunakan Bank Soal
    ];

    // ── Relasi ─────────────────────────────────────────────────────
   public function creator()    { return $this->belongsTo(User::class, 'created_by'); }
public function grupSoal()   { return $this->belongsTo(GrupSoal::class, 'grup_soal_id'); }
public function passage()    { return $this->belongsTo(Passage::class, 'passage_id'); }
public function modul()      { return $this->belongsTo(ModulSoal::class, 'modul_id'); }
public function audioPaket() { return $this->belongsTo(ListeningAudioPaket::class, 'audio_paket_id'); }
    // ── Helper ─────────────────────────────────────────────────────
    public function getPilihanAttribute(): array {
        $p = [];
        foreach (['a','b','c','d','e','f'] as $k) {
            $col = 'pilihan_' . $k;
            if (!empty($this->$col)) $p[$k] = $this->$col;
        }
        return $p;
    }

    public function getJawabanBenarArrayAttribute(): array {
        if ($this->tipe_soal === 'prose_summary' && $this->jawaban_benar_multiple)
            return explode(',', $this->jawaban_benar_multiple);
        return [$this->jawaban_benar];
    }

    public function getTipeLabelAttribute(): string {
        foreach (self::TIPE_PER_SECTION as $section => $types) {
            if (isset($types[$this->tipe_soal])) return $types[$this->tipe_soal];
        }
        return ucfirst($this->tipe_soal ?? 'unknown');
    }

    // ── Scopes ─────────────────────────────────────────────────────
    public function scopeKategori($q, string $kat)  { return $q->where('kategori', $kat); }
    public function scopeTipePaket($q, string $tipe) { return $q->where('tipe_paket', $tipe); }
    // Bank Soal hanya untuk Tes Full
    public function scopeFullOnly($q) { return $q->where('untuk_tes_full', 1); }
    public function scopeAktif($q)                  { return $q->where('is_aktif', true); }
}
