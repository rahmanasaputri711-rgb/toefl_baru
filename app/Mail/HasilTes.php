<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\PercobaanTes;

class HasilTes extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public PercobaanTes $percobaan) {}

    public function envelope(): Envelope
    {
        $lulus   = $this->percobaan->skor_total >= 500;
        $prefix  = $lulus ? '🎉 Selamat! Kamu Lulus' : '💪 Hasil Tes TOEFL ITP';
        $skor    = $this->percobaan->skor_total;
        return new Envelope(
            subject: "{$prefix} — Skor: {$skor} | TOEFL ITP Polman"
        );
    }

    public function content(): Content
    {
        $lulus = $this->percobaan->skor_total >= 500;
        return new Content(
            view: $lulus ? 'emails.hasil-lulus' : 'emails.hasil-gagal'
        );
    }
}
