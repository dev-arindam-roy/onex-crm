<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Events\ClientWelcomeMailEvent;
use App\Mail\ClientWelcomeMail;

class ClientWelcomeMailListener implements ShouldQueue
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
    public function handle(ClientWelcomeMailEvent $event)
    {
        $email = $event->user->email_id;
        \Log::info("LISTENER:: Client Welcome Mail " . $email);
        Mail::to($email)->send(new ClientWelcomeMail($event->user));
    }
}
