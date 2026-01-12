<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmarCuentaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function build()
    {
        $url = url('/confirmar-cuenta/'.$this->token);

        return $this->subject('Confirma tu cuenta de acceso')
            ->view('emails.confirmar-cuenta')
            ->with([
                'url' => $url
            ]);
    }
}
