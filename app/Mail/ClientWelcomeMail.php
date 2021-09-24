<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class ClientWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        \Log::info("MAIL:: Client Welcome Email = " . $this->user->email_id);
        $DataBag = [];
        $subject = "ONEX-CRM - Welcome To Account";
        $DataBag['user'] = $this->user;
        $DataBag['organization'] = $this->user->businessAccount;
        return $this->subject($subject)->view('mails.client_welcome', $DataBag);
    }
}
