<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\PendaftaranTes;

class KonfirmasiPendaftaran extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public PendaftaranTes $pendaftaran) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: '✅ Pendaftaran Tes TOEFL ITP Dikonfirmasi — ' . $this->pendaftaran->nomor_pendaftaran);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.konfirmasi-pendaftaran');
    }
}
