<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\ResetPassword;
use OnexHelper;

class ClientForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $resetPassword;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ResetPassword $resetPassword)
    {
        $this->resetPassword = $resetPassword;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        \Log::info("MAIL:: Client Forgot Password Email = " . $this->resetPassword->email_id);
        $DataBag = [];
        $subject = "ONEX-CRM - Reset Password";
        $DataBag['reset_password'] = $this->resetPassword;
        $DataBag['user'] = OnexHelper::userByEmail($this->resetPassword->email_id);
        return $this->subject($subject)->view('mails.client_forgot_password', $DataBag);
    }
}
