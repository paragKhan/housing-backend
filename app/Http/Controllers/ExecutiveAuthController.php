<?php

namespace App\Http\Controllers;

use App\Models\Executive;
use Illuminate\Http\Request;

class ExecutiveAuthController extends Controller
{
    public function login(Request $request)
    {
        return Executive::login($request);
    }

    public function logout()
    {
        return Executive::logout();
    }
}
