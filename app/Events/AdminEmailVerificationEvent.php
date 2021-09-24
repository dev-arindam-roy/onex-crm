<?php

namespace App\Events;


use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Admin;

class AdminEmailVerificationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $admin;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Admin $admin)
    {
        \Log::info('EVENT:: Admin Account Email Verification' . $admin->email_id);
        $this->admin = $admin;
    }
}
