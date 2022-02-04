<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserSignupRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

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

    public function verifyToken(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer'
        ]);
        if (auth()->id() == $validated["user_id"]) {
            return true;
        }

        abort(401);
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
}
