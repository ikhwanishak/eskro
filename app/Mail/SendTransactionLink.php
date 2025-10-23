namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendTransactionLink extends Mailable
{
    use Queueable, SerializesModels;

    public $link;
    public $otp;

    public function __construct($link, $otp)
    {
        $this->link = $link;
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->subject('[Eskro] Access Your Transaction')
            ->view('emails.transaction_link');
    }
}
