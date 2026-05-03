<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\PendaftaranTes;

class TolakPendaftaran extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public PendaftaranTes $pendaftaran) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '❌ Pendaftaran Tes TOEFL ITP Tidak Dikonfirmasi — ' .
                     $this->pendaftaran->sesiTes?->judul
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.tolak-pendaftaran');
    }
}
