<?php

namespace App\Models;

use App\Traits\LoginTrait;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements CanResetPassword
{
    use HasFactory, Notifiable, LoginTrait;

    protected $guarded = [];
    protected $hidden = ['password'];
    protected static $guardName = 'user';

    public function applications(){
        return $this->hasMany(Application::class);
    }
}
