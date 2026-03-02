<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->subject('Kode Verifikasi OTP - Perpustakaan')
            ->html("<h3>Kode OTP Anda adalah: <b>{$this->otp}</b></h3><p>Kode ini berlaku selama 5 menit. Jangan berikan kode ini kepada siapapun.</p>");
    }
}
