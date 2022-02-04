<?php

namespace App\Models;

use App\Traits\LoginTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, LoginTrait;

    protected $guarded = [];
    protected $hidden = ['password'];
    protected static $guardName = 'user';
}
