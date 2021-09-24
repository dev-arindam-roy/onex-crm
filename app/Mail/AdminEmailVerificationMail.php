<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Admin;

class AdminEmailVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $admin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        \Log::info("MAIL:: Admin Account Email Verification = " . $this->admin->email_id);
        $DataBag = [];
        $subject = "ONEX-Master - Account Verification Email";
        $DataBag['admin'] = $this->admin;
        return $this->subject($subject)->view('mails.admin_email_verification', $DataBag);
    }
}
