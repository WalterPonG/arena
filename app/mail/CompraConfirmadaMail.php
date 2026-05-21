<?php
namespace App\Mail;
use Illuminate\Mail\Mailable;

class CompraConfirmadaMail extends Mailable
{
    public $entradas;

    public function __construct($entradas)
    {
        $this->entradas = $entradas;
    }

    public function build()
    {
        return $this->subject('Confirmación de compra - Roig Arena')
            ->view('emails.compra-confirmada');
    }
}
