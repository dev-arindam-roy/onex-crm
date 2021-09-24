<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Events\AdminForgotPasswordMailEvent;
use App\Mail\AdminForgotPasswordMail;

class AdminForgotPasswordMailListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(AdminForgotPasswordMailEvent $event)
    {
        $email = $event->resetPassword->email_id;
        \Log::info("LISTENER:: Admin Forgot Password Mail " . $email);
        Mail::to($email)->send(new AdminForgotPasswordMail($event->resetPassword));
    }
}
