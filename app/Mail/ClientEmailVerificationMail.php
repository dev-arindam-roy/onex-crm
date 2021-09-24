<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class ClientEmailVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $type;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $type = '')
    {
        $this->user = $user;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        \Log::info("MAIL:: Account Email Verification = " . $this->user->email_id);
        $DataBag = [];
        $subject = "ONEX-CRM - Account Verification Email";
        $DataBag['user'] = $this->user;
        $DataBag['mailType'] = $this->type;
        return $this->subject($subject)->view('mails.client_email_verification', $DataBag);
    }
}
