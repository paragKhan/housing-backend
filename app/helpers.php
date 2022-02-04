<?php

use App\Models\Admin;
use App\Models\Approver;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Support\Str;

function isAdmin(){
    return auth()->check() && auth()->user() instanceof Admin;
}

function isApprover(){
    return auth()->check() && (auth()->user() instanceof Approver);
}

function isManager(){
    return auth()->check() && auth()->user() instanceof Manager;
}

function isUser(){
    return auth()->check() && auth()->user() instanceof User;
}
