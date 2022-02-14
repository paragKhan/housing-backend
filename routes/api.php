<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ApproverController;
use App\Http\Controllers\HousingModelController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\SubdivisionController;
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
    Route::post('messages', [MessageController::class, 'store']);

    //Email Verification
    Route::post('send-verification-email', [UserAuthController::class, 'sendVerificationEmail'])->middleware('auth:api_user');
    Route::get('verify-email/{id}/{hash}', [UserAuthController::class, 'verifyEmail'])->name('verification.verify')->middleware('auth:api_user');

    //Forgot and Reset password
    Route::post('forgot-password', [UserAuthController::class, "forgotPassword"]);
    Route::post('reset-password', [UserAuthController::class, "resetPassword"])->name('password.reset');

    Route::get('subdivisions/get-locations', [SubdivisionController::class, "getLocations"]);
    Route::apiResource('subdivisions', SubdivisionController::class)->only('index', 'show');
    Route::get('housing_models/get-queries', [HousingModelController::class, "getQueries"]);
    Route::apiResource('housing_models', HousingModelController::class)->only('index', 'show');

    Route::middleware('auth:api_user')->group(function () {
        //Upload photo
        Route::post('upload_photo', [PhotoController::class, 'upload']);
        Route::get('logout', [UserAuthController::class, 'logout']);
        Route::get('profile', [UserAuthController::class, 'getProfile']);
        Route::put('profile', [UserAuthController::class, 'updateProfile']);

        //applications
        Route::post('apply', [ApplicationController::class, 'store']);
        Route::get('can-submit-application', [ApplicationController::class, 'canSubmitApplication']);
        Route::get('get-application-status', [ApplicationController::class, 'getApplicationStatus']);
    });

});

Route::prefix('approver')->group(function () {
    Route::post('login', function (Request $request) {
        return Approver::login($request);
    });

    Route::middleware('auth:api_approver')->group(function (){
        Route::apiResource('applications', ApplicationController::class);
        Route::apiResource('messages', MessageController::class)->except('create', 'update');
    });
});

Route::prefix('manager')->group(function () {
    Route::post('login', function (Request $request) {
        return Manager::login($request);
    });

    Route::middleware('auth:api_manager')->group(function (){
        Route::apiResource('subdivisions', SubdivisionController::class);
        Route::apiResource('housing_models', HousingModelController::class);
    });
});

Route::prefix('admin')->group(function () {
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::middleware('auth:api_admin')->group(function () {
        Route::post('upload_photo', [PhotoController::class, 'upload']);
        Route::get('logout', [AdminAuthController::class, 'logout']);
        Route::apiResource('approvers', ApproverController::class);
        Route::apiResource('managers', ManagerController::class);
        Route::apiResource('users', UserController::class)->except('destroy');
        Route::apiResource('applications', ApplicationController::class);
        Route::apiResource('subdivisions', SubdivisionController::class);
        Route::apiResource('housing_models', HousingModelController::class);
        Route::apiResource('messages', MessageController::class)->except('create', 'update');
    });

});

