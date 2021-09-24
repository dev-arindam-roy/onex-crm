<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $hidden = [
        'password',
        'remember_token',
        'email_verify_token'
    ];

    public function businessAccount() {
        return $this->hasOne('App\Models\BusinessAccount', 'user_id', 'id');
    }
}
