<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order_request;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order_request)
    {
        $this->order_request = $order_request;
        $this->subject('Order Request Tracking #:' . $order_request->tracking_no);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.order_request');
    }
}
