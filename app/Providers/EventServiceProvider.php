<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\ClientEmailVerificationEvent;
use App\Listeners\ClientEmailVerificationListener;
use App\Events\ClientWelcomeMailEvent;
use App\Listeners\ClientWelcomeMailListener;
use App\Events\ClientForgotPasswordMailEvent;
use App\Listeners\ClientForgotPasswordMailListener;
use App\Events\AdminEmailVerificationEvent;
use App\Listeners\AdminEmailVerificationListener;
use App\Events\AdminForgotPasswordMailEvent;
use App\Listeners\AdminForgotPasswordMailListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ClientEmailVerificationEvent::class => [
            ClientEmailVerificationListener::class,
        ],
        ClientWelcomeMailEvent::class => [
            ClientWelcomeMailListener::class,
        ],
        ClientForgotPasswordMailEvent::class => [
            ClientForgotPasswordMailListener::class,
        ],
        AdminEmailVerificationEvent::class => [
            AdminEmailVerificationListener::class,
        ],
        AdminForgotPasswordMailEvent::class => [
            AdminForgotPasswordMailListener::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
