<?php

namespace App\Mail;

use App\Models\CamoActivity;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CamoActivityUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public CamoActivity $camoActivity;

    /**
     * Create a new message instance.
     */
    public function __construct(CamoActivity $camoActivity)
    {
        $this->camoActivity = $camoActivity;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Camo Activity Update',
            tags: ['activities'],
            metadata: [
                'user' => $this->camoActivity->camo->owner,
                'camo_id' => $this->camoActivity->camo,
            ]
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.update-camo-activity',
            with: [
                'user' => $this->camoActivity->camo->owner,
                'camoActivity' => $this->camoActivity
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
