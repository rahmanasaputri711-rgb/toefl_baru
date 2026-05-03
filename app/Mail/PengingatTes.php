<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\PendaftaranTes;

class PengingatTes extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public PendaftaranTes $pendaftaran) {}

    public function envelope(): Envelope
    {
        $tanggal = \Carbon\Carbon::parse($this->pendaftaran->sesiTes->waktu_mulai)
            ->format('d M Y');
        return new Envelope(
            subject: "⏰ Pengingat: Tes TOEFL ITP Besok {$tanggal} — Jangan Lupa Hadir!"
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.pengingat-tes');
    }
}
