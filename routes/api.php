<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ApproverAuthController;
use App\Http\Controllers\ApproverController;
use App\Http\Controllers\ExecutiveAuthController;
use App\Http\Controllers\ExecutiveController;
use App\Http\Controllers\HousingModelController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\StaffAuthController;
use App\Http\Controllers\StaffController;
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
    Route::get('subdivisions/for-application', [SubdivisionController::class, 'forApplication']);
    Route::apiResource('subdivisions', SubdivisionController::class)->only('index', 'show');

    Route::get('housing_models/for-application', [HousingModelController::class, 'forApplication']);
    Route::get('housing_models/get-queries', [HousingModelController::class, "getQueries"]);
    Route::apiResource('housing_models', HousingModelController::class)->only('index', 'show');

    Route::middleware('auth:api_user')->group(function () {
        //Upload photo
        Route::get('logout', [UserAuthController::class, 'logout']);
        Route::get('profile', [UserAuthController::class, 'getProfile']);
        Route::put('profile', [UserAuthController::class, 'updateProfile']);

        //applications
        Route::post('apply', [ApplicationController::class, 'store']);
        Route::get('can-submit-application', [ApplicationController::class, 'canSubmitApplication']);
        Route::get('get-application-status', [ApplicationController::class, 'getApplicationStatus']);
    });

});

Route::prefix('staff')->group(function () {
    Route::post('login', [StaffAuthController::class, 'login']);

    Route::middleware('auth:api_staff')->group(function () {
        Route::get('logout', [StaffAuthController::class, 'logout']);
        Route::get('applications/{application}/forward', [ApplicationController::class, 'forward']);
        Route::get('applications/filter-queries', [ApplicationController::class, 'getFilterQueries']);
        Route::apiResource('applications', ApplicationController::class)->except('create', 'delete');
    });
});

Route::prefix('executive')->group(function () {
    Route::post('login', [ExecutiveAuthController::class, 'login']);

    Route::middleware('auth:api_executive')->group(function () {
        Route::get('logout', [ExecutiveAuthController::class, 'logout']);
        Route::get('applications/filter-queries', [ApplicationController::class, 'getFilterQueries']);
        Route::apiResource('applications', ApplicationController::class);
    });
});

Route::prefix('approver')->group(function () {
    Route::post('login', [ApproverAuthController::class, 'login']);

    Route::middleware('auth:api_approver')->group(function () {
        Route::get('logout', [ApproverAuthController::class, 'logout']);
        Route::apiResource('applications', ApplicationController::class);
        Route::apiResource('messages', MessageController::class)->except('create', 'update');
    });
});

Route::prefix('manager')->group(function () {
    Route::post('login', function (Request $request) {
        return Manager::login($request);
    });

    Route::middleware('auth:api_manager')->group(function () {
        Route::get('logout', function () {
            return Manager::logout();
        });
        Route::apiResource('subdivisions', SubdivisionController::class);
        Route::apiResource('housing_models', HousingModelController::class);
    });
});

Route::prefix('admin')->group(function () {
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::middleware('auth:api_admin')->group(function () {
        Route::get('logout', [AdminAuthController::class, 'logout']);
        Route::apiResource('approvers', ApproverController::class);
        Route::apiResource('managers', ManagerController::class);
        Route::apiResource('executives', ExecutiveController::class);
        Route::apiResource('staff', StaffController::class);
        Route::apiResource('users', UserController::class);
        Route::get('applications/filter-queries', [ApplicationController::class, 'getFilterQueries']);
        Route::apiResource('applications', ApplicationController::class);
        Route::apiResource('subdivisions', SubdivisionController::class);
        Route::apiResource('housing_models', HousingModelController::class);
        Route::apiResource('messages', MessageController::class)->except('create', 'update');
    });

});

