<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvitationEmailMailable extends Mailable
{
    use Queueable, SerializesModels;

    private $senderName;
    private $groupName;

    private $groupId;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($senderName,$groupName,$groupId)
    {
        $this->senderName = $senderName;
        $this->groupName = $groupName;
        $this->groupId = $groupId;
        //
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Invitation Email',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'mails.group_invitation',
            with: [
                'senderName' => $this->senderName,
                'groupName' => $this->groupName,
                'groupId' => $this->groupId,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
