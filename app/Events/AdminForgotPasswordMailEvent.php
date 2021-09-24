<?php

namespace App\Events;


use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\ResetPassword;

class AdminForgotPasswordMailEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $resetPassword;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ResetPassword $resetPassword)
    {
        \Log::info('EVENT:: Admin Forgot Password Mail ' . $resetPassword->email_id);
        $this->resetPassword = $resetPassword;
    }
}
