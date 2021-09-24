<?php

namespace App\Events;


use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class ClientEmailVerificationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $type;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $type = '')
    {
        \Log::info('EVENT:: Account Email Verification' . $user->email_id);
        $this->user = $user;
        $this->type = $type;
    }
}
