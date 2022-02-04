<?php

use App\Http\Controllers\ApproverController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserController;
use App\Http\Requests\StoreUserSignupRequest;
use App\Models\Admin;
use App\Models\Approver;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('testing', function (Request $request) {
    return auth()->user();
})->middleware('auth:sanctum');

//User
Route::prefix('user')->group(function () {
    Route::post('login', [UserAuthController::class, 'login']);
    Route::post('signup', [UserAuthController::class, 'signup']);

    Route::middleware('auth:api_user')->group(function () {
        //Upload photo
        Route::post('upload_photo', [PhotoController::class, 'upload']);
        Route::get('logout', [UserAuthController::class, 'logout']);
        Route::post('verify-token', [UserAuthController::class, 'verifyToken']);
        Route::get('profile', [UserAuthController::class, 'getProfile']);
        Route::put('profile', [UserAuthController::class, 'updateProfile']);
    });

});

Route::prefix('approver')->group(function () {
    Route::post('login', function (Request $request) {
        return Approver::login($request);
    });
});

Route::prefix('manager')->group(function () {
    Route::post('login', function (Request $request) {
        return Manager::login($request);
    });
});

Route::prefix('admin')->group(function () {
    Route::post('login', function (Request $request) {
        return Admin::login($request);
    });
    Route::middleware('auth:api_admin')->group(function () {
        Route::apiResource('approvers', ApproverController::class)->except('destroy');
        Route::apiResource('managers', ManagerController::class)->except('destroy');
        Route::apiResource('users', UserController::class)->except('destroy');
    });

});

