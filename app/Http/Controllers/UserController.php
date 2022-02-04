<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index()
    {
        return response()->json(User::paginate(20));
    }

    public function store(StoreUserRequest $request)
    {
        return response()->json(User::create($request->validated()));
    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $previous_photo = $user->photo;

        $user->update($request->validated());

        deleteImage($previous_photo);

        return response()->json($user);
    }

    public function destroy(User $user)
    {
        //
    }
}
