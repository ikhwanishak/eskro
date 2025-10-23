<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransactionInvite extends Mailable
{
    use Queueable, SerializesModels;

    public $transaction, $tac, $link, $creator;

    public function __construct($transaction, $tac, $link, $creator)
    {
        $this->transaction = $transaction;
        $this->tac = $tac;
        $this->link = $link;
        $this->creator = $creator;
    }

    public function build()
    {
        return $this->subject("You've Been Added to a Transaction")
                    ->view('emails.transaction_invite');
    }
}
