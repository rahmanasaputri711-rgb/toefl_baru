<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PendaftaranTes extends Model {
    protected $table    = 'pendaftaran_tes';
    protected $fillable = [
        'user_id','sesi_id','nim_nip','status_polman','program_studi',
        'no_telepon','berkas_identitas_url','status_pendaftaran','catatan_admin',
        'email_sent','dikonfirmasi_oleh','confirmed_at','email_sent_at','nomor_pendaftaran',
        'dibatalkan_at','alasan_batal','is_hadir','ditandai_absen_at',
    ];
    protected $casts = [
        'confirmed_at'      => 'datetime',
        'dibatalkan_at'     => 'datetime',
        'ditandai_absen_at' => 'datetime',
        'is_hadir'          => 'boolean',
    ];
    public function user()    { return $this->belongsTo(User::class); }
    public function sesiTes() { return $this->belongsTo(SesiTes::class,'sesi_id'); }
    public function logs()    { return $this->hasMany(LogPendaftaran::class,'pendaftaran_id'); }

    /** Apakah masih bisa dibatalkan user? (status menunggu & > H-2) */
    public function bisaDibatalkanUser(): bool {
        if ($this->status_pendaftaran !== 'menunggu') return false;
        if (!$this->sesiTes || !$this->sesiTes->waktu_mulai) return true;
        return now()->lt($this->sesiTes->waktu_mulai->subDays(2));
    }
}
