<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Events\ClientEmailVerificationEvent;
use App\Mail\ClientEmailVerificationMail;

class ClientEmailVerificationListener implements ShouldQueue
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
    public function handle(ClientEmailVerificationEvent $event)
    {
        $email = $event->user->email_id;
        \Log::info("LISTENER:: Account Email Verification" . $email);
        Mail::to($email)->send(new ClientEmailVerificationMail($event->user, $event->type));
    }
}
