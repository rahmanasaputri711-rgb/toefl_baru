<?php
namespace App\Console\Commands;

use App\Models\PendaftaranTes;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class KirimPengingatTes extends Command
{
    protected $signature   = 'notif:pengingat-tes';
    protected $description = 'Kirim email + push notification H-1 sebelum tes (jalankan tiap hari jam 18.00)';

    public function handle(NotificationService $notif): void
    {
        $besok = Carbon::tomorrow()->format('Y-m-d');

        $pendaftaran = PendaftaranTes::with(['user','sesiTes'])
            ->where('status_pendaftaran', 'dikonfirmasi')
            ->whereHas('sesiTes', fn($q) =>
                $q->whereDate('waktu_mulai', $besok)
            )->get();

        $this->info("Mengirim pengingat ke {$pendaftaran->count()} peserta...");

        foreach ($pendaftaran as $p) {
            if (!$p->user || !$p->sesiTes) continue;

            $jam = Carbon::parse($p->sesiTes->waktu_mulai)->format('H:i');

            // 1. Kirim EMAIL
            try {
                Mail::to($p->user->email)
                    ->send(new \App\Mail\PengingatTes($p));
                $this->line("  📧 Email → {$p->user->email}");
            } catch (\Exception $e) {
                $this->error("  ✗ Email gagal ke {$p->user->email}: {$e->getMessage()}");
            }

            // 2. Kirim PUSH NOTIFICATION (FCM)
            $notif->kirimNotifikasi(
                $p->user,
                '⏰ Tes TOEFL Besok!',
                "Kamu terdaftar tes besok pukul {$jam} WIB. " .
                "Nomor: {$p->nomor_pendaftaran}. Hadir tepat waktu!",
                'warning',
                '/pendaftaran/status',
                true
            );
            $this->line("  🔔 Push → {$p->user->name}");
        }

        $this->info('Selesai.');
    }
}
