<?php

namespace App\Mail;

use App\Models\Pembayaran;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PembayaranBerhasil extends Mailable
{
    use Queueable, SerializesModels;

    public $pembayaran;

    /**
     * Create a new message instance.
     */
    public function __construct(Pembayaran $pembayaran)
    {
        $this->pembayaran = $pembayaran;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Konfirmasi Pembayaran SIMAKSI - ' . $this->pembayaran->pendaftaran->id_pendaftaran,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.pembayaran-berhasil',
            with: [
                'pembayaran' => $this->pembayaran,
                'pendaftaran' => $this->pembayaran->pendaftaran,
                'user' => $this->pembayaran->user,
                'gunung' => $this->pembayaran->pendaftaran->rutePendakian->gunung ?? null,
                'rute' => $this->pembayaran->pendaftaran->rutePendakian ?? null,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
