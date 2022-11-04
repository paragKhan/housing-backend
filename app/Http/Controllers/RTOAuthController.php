<?php

namespace App\Http\Controllers;

use App\Models\RTO;
use Illuminate\Http\Request;

class RTOAuthController extends Controller
{
    public function login(Request $request)
    {
        return RTO::login($request);
    }

    public function logout()
    {
        return RTO::logout();
    }
}
