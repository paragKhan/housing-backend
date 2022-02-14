<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserSignupRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class UserAuthController extends Controller
{
    public function signup(StoreUserSignupRequest $request)
    {
        User::create($request->validated());

        return response()->json(['message' => 'Signup successful']);
    }

    public function login(Request $request)
    {
        return User::login($request);
    }

    public function logout()
    {
        return User::logout();
    }

    public function getProfile()
    {
        return response()->json(auth()->user());
    }

    public function updateProfile(UpdateUserRequest $request)
    {
        $user = $request->user();
        $previous_photo = $user->photo;

        $user->update($request->validated());

        if ($user->photo != $previous_photo)
            PhotoController::delete($previous_photo);

        return response()->json($user);
    }

    public function sendVerificationEmail(Request $request){
        if($request->user()->hasVerifiedEmail()){
            return response()->json(['message' => 'Already Verified']);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification link sent']);
    }

    public function verifyEmail(EmailVerificationRequest $request){
        if($request->user()->hasVerifiedEmail()){
            return response()->json(['message'=>'Email already verified']);
        }

        if($request->user()->markEmailAsVerified()){
            event(new Verified($request->user()));
        }

        return response()->json('Email has been verified');
    }

    public function forgotPassword(Request $request){
        $request->validate([
            'email' => 'required|email'
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if($status == Password::RESET_LINK_SENT){
            return ['message' => __($status)];
        }

        return response(['message'=> __($status)], 500);
    }

    public function resetPassword(Request $request){
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'token'),
            function($user) use($request) {
                $user->update(['password' => Hash::make($request->password)]);
                event(new PasswordReset($user));
            }
        );

        if($status == Password::PASSWORD_RESET){
            return ['message' => __($status)];
        }

        return response(['message'=> __($status)], 500);
    }
}
