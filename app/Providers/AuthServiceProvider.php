<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Password;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        VerifyEmail::toMailUsing(function($notifiable, $url){
            $spaUrl = "mothbahamas.com/verify-email?url=$url";

            return (new MailMessage)
                ->subject('Verify Email Address')
                ->line('Click the button bellow to verify your email address')
                ->action('Verify Email Address', $spaUrl);
        });

        ResetPassword::toMailUsing(function($user, $token){
            $spaUrl = "mothbahamas.com/reset-password?email=".$user->email."&token=".$token;

            return (new MailMessage)
                ->subject("Reset Password")
                ->line("Click the button bellow to reset your password")
                ->action('Reset Password', $spaUrl);
        });
    }
}
