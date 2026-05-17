<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ListeningAudioPaket extends Model
{
    protected $table    = 'listening_audio_paket';
    protected $fillable = [
        'nama','tipe_paket','tipe_upload',
        'paket_soal_id','urutan_modul','offset_detik','keterangan',
        'audio_url','durasi_detik','jumlah_soal','is_aktif','created_by',
    ];
    protected $casts = ['is_aktif' => 'boolean'];

    // ── Relasi ──────────────────────────────────────────────────
    public function creator()   { return $this->belongsTo(User::class, 'created_by'); }
    public function paketSoal() { return $this->belongsTo(PaketSoal::class, 'paket_soal_id'); }
    public function soalList()  {
        return $this->hasMany(BankSoal::class, 'audio_paket_id')
                    ->orderBy('order_number');
    }
    // Modul yang pakai audio ini
    public function modulList() {
        return $this->hasMany(ModulSoal::class, 'audio_paket_id');
    }

    // ── Accessor ────────────────────────────────────────────────
    public function getAudioUrlFullAttribute(): string {
        if (!$this->audio_url) return '';
        return str_starts_with($this->audio_url, 'http')
            ? $this->audio_url
            : asset('storage/' . $this->audio_url);
    }

    public function getDurasiFormatAttribute(): string {
        $m = intdiv($this->durasi_detik, 60);
        $s = $this->durasi_detik % 60;
        return sprintf('%d:%02d', $m, $s);
    }

    public function getTipeUploadLabelAttribute(): string {
        return $this->tipe_upload === 'paket'
            ? '📦 1 Audio Full Paket'
            : '🧩 Audio Per Modul';
    }
}
