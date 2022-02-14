<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserSignupRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
}
