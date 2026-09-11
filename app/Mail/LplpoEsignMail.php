<?php

namespace App\Mail;

use App\Models\NewLplpo\InfoKapus;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LplpoEsignMail extends Mailable
{
    use Queueable, SerializesModels;

    public InfoKapus $kapus;

    public string $kodeEsign;

    public function __construct(
        InfoKapus $kapus,
        string $kodeEsign
    ) {
        $this->kapus = $kapus;
        $this->kodeEsign = $kodeEsign;
    }

    public function build()
    {
        return $this
            ->subject(
                'Kode E-Sign LPLPO - '
                . $this->kapus->namaKapus
            )
            ->view('emails.newlplpo.esign');
    }
}