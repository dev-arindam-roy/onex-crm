<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\ResetPassword;
use App\Traits\AdminHelperTrait as AdminHelper;

class AdminForgotPasswordMail extends Mailable
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
        \Log::info("MAIL:: Admin Forgot Password Email = " . $this->resetPassword->email_id);
        $DataBag = [];
        $subject = "ONEX-Master - Reset Password";
        $DataBag['reset_password'] = $this->resetPassword;
        $DataBag['admin'] = AdminHelper::adminByEmail($this->resetPassword->email_id);
        return $this->subject($subject)->view('mails.admin_forgot_password', $DataBag);
    }
}
