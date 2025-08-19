<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SolicitudAutorizacion extends Mailable
{
    use Queueable, SerializesModels;

    public $numChasis;
    public $token;
    public $usuarioSolicita;

    public function __construct($numChasis, $token, $usuarioSolicita)
    {
        $this->numChasis = $numChasis;
        $this->token = $token;
        $this->usuarioSolicita = $usuarioSolicita;
    }

    public function build()
    {
        return $this->subject("Solicitud de Autorización de Impresión: {$this->numChasis}")
                    ->markdown('emails.autorizacion');
    }
}
