<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendTAC extends Mailable
{
    use Queueable, SerializesModels;

    public $tac;

    /**
     * Create a new message instance.
     */
    public function __construct($tac)
    {
        $this->tac = $tac;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your TAC Code')
                    ->view('emails.verify_tac')
                    ->with(['tac' => $this->tac]);
    }
}
