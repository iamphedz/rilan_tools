<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;
    public $contact_message;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($contact_message)
    {
        $this->contact_message = $contact_message;
        $this->subject('Message From: ' . $contact_message['name']);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.contact_form');
    }
}
