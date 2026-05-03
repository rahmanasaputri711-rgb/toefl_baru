<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class User extends Authenticatable {
    use HasFactory, Notifiable;
    protected $table    = 'users';
    protected $fillable = [
        'name','email','password','role','is_active',
        'google_id','avatar','last_login_at','email_verified_at',
        'cooldown_sampai','jumlah_absen','fcm_token',
    ];
    protected $hidden = ['password','remember_token'];
    protected $casts  = [
        'email_verified_at' => 'datetime',
        'last_login_at'     => 'datetime',
        'cooldown_sampai'   => 'datetime',
        'is_active'         => 'boolean',
    ];

    public function pendaftaran()  { return $this->hasMany(PendaftaranTes::class); }
    public function notifikasi()   { return $this->hasMany(Notifikasi::class); }
    public function percobaan()    { return $this->hasMany(PercobaanTes::class); }

    /** Cooldown dihapus dari sistem — selalu return false */
    public function dalamCooldown(): bool {
        return false;
    }

    /** Apakah user diblokir karena terlalu banyak absen? */
    public function diblokir(): bool {
        return $this->jumlah_absen >= 3;
    }

    /** Notifikasi belum dibaca */
    public function notifikasiUnread() {
        return $this->notifikasi()->where('is_read', 0);
    }
}
