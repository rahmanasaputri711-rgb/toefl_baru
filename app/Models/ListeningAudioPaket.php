<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ListeningAudioPaket extends Model
{
    protected $table    = 'listening_audio_paket';
    protected $fillable = [
        'nama','tipe_paket','audio_url',
        'durasi_detik','jumlah_soal','is_aktif','created_by'
    ];
    protected $casts = ['is_aktif' => 'boolean'];

    public function creator()  { return $this->belongsTo(User::class, 'created_by'); }
    public function soalList() {
        return $this->hasMany(BankSoal::class, 'audio_paket_id')
                    ->orderBy('order_number');
    }

    /** URL audio yang bisa diakses browser */
    public function getAudioUrlFullAttribute(): string {
        if (!$this->audio_url) return '';
        return str_starts_with($this->audio_url, 'http')
            ? $this->audio_url
            : asset('storage/' . $this->audio_url);
    }

    /** Format durasi: 2100 → "35:00" */
    public function getDurasiFormatAttribute(): string {
        $m = intdiv($this->durasi_detik, 60);
        $s = $this->durasi_detik % 60;
        return sprintf('%d:%02d', $m, $s);
    }
}
