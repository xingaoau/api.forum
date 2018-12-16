<?php

namespace App\Mail;

use UrlSigner;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPassword extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $email;
    public $token;
    public $link;

    /**
     * UserForgetPassword constructor.
     *
     * @param string $email
     * @param string $token
     */
    public function __construct(string $email, string $token)
    {
        $this->email = $email;
        $this->token = $token;
        $this->link = $this->resetPasswordLink();
    }

    public function resetPasswordLink()
    {
        $params = [
            'token' => $this->token,
            'email' => $this->email
        ];
        return config('app.site_url').'user/reset-password?'.http_build_query($params);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('reset password')->markdown('mails.reset-password');
    }
}
