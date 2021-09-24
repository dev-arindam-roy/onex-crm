<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Events\AdminEmailVerificationEvent;
use App\Mail\AdminEmailVerificationMail;

class AdminEmailVerificationListener implements ShouldQueue
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
    public function handle(AdminEmailVerificationEvent $event)
    {
        $email = $event->admin->email_id;
        \Log::info("LISTENER:: Admin Account Email Verification" . $email);
        Mail::to($email)->send(new AdminEmailVerificationMail($event->admin));
    }
}
