<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Events\ClientForgotPasswordMailEvent;
use App\Mail\ClientForgotPasswordMail;

class ClientForgotPasswordMailListener implements ShouldQueue
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
    public function handle(ClientForgotPasswordMailEvent $event)
    {
        $email = $event->resetPassword->email_id;
        \Log::info("LISTENER:: Client Forgot Password Mail " . $email);
        Mail::to($email)->send(new ClientForgotPasswordMail($event->resetPassword));
    }
}
