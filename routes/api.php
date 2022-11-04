<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ApproverAuthController;
use App\Http\Controllers\ApproverController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ExecutiveAuthController;
use App\Http\Controllers\ExecutiveController;
use App\Http\Controllers\HousingModelController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\RentToOwnApplicationController;
use App\Http\Controllers\RTOAuthController;
use App\Http\Controllers\RTOController;
use App\Http\Controllers\StaffAuthController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\SubdivisionController;
use App\Http\Controllers\SupportConversationController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use App\Models\Manager;
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
    Route::apiResource('photos', PhotoController::class)->only('index', 'show');
    Route::apiResource('videos', VideoController::class)->only('index', 'show');
    Route::apiResource('blogs', BlogController::class)->only('index', 'show');

    Route::middleware('auth:api_user')->group(function () {
        //Upload photo
        Route::get('logout', [UserAuthController::class, 'logout']);
        Route::get('profile', [UserAuthController::class, 'getProfile']);
        Route::put('profile', [UserAuthController::class, 'updateProfile']);

        //applications
        Route::post('apply', [ApplicationController::class, 'store']);
        //todo: merge bellow routes into one
        Route::get('can-submit-application', [ApplicationController::class, 'canSubmitApplication']);
        Route::get('get-application-status', [ApplicationController::class, 'getApplicationStatus']);

        //rto-applications
        Route::post('apply-rto', [RentToOwnApplicationController::class, 'store']);
        Route::get('get-rto-application-status', [RentToOwnApplicationController::class, 'getApplicationStatus']);

        //support
        Route::get('support_conversations/{conversation}/resolve', [SupportConversationController::class, 'resolveConversation']);
        Route::get('support_conversations/history', [SupportConversationController::class, 'myHistory']);
        Route::post('support_conversations/{conversation}/send-message', [SupportConversationController::class, 'sendMessage']);
        Route::apiResource('support_conversations', SupportConversationController::class)->except('index', 'update', 'destroy');
    });

});

Route::prefix('staff')->group(function () {
    Route::post('login', [StaffAuthController::class, 'login']);

    Route::middleware('auth:api_staff')->group(function () {
        Route::get('logout', [StaffAuthController::class, 'logout']);

        //support
        Route::get('support_conversations/{conversation}/resolve', [SupportConversationController::class, 'resolveConversation']);
        Route::post('support_conversations/{conversation}/send-message', [SupportConversationController::class, 'sendMessage']);
        Route::apiResource('support_conversations', SupportConversationController::class)->except('update');

        Route::get('applications/{application}/forward', [ApplicationController::class, 'forward']);
        Route::get('applications/filter-queries', [ApplicationController::class, 'getFilterQueries']);
        Route::apiResource('applications', ApplicationController::class)->except('store', 'delete');
        Route::apiResource('messages', MessageController::class)->except('store', 'update');

        Route::prefix('dashboard')->group(function () {
            Route::get('get-overview', [AdminDashboardController::class, 'getOverview']);
            Route::get('get-application-stats', [AdminDashboardController::class, 'getApplicationStats']);
            Route::get('get-user-joining-stats', [AdminDashboardController::class, 'getUserJoiningStats']);
            Route::get('get-message-stats', [AdminDashboardController::class, 'getMessageStats']);
            Route::get('get-support-ticket-stats', [AdminDashboardController::class, 'getSupportTicketStats']);
            Route::get('get-subdivision-stats', [AdminDashboardController::class, 'getSubdivisionStats']);
        });
    });
});

Route::prefix('executive')->group(function () {
    Route::post('login', [ExecutiveAuthController::class, 'login']);

    Route::middleware('auth:api_executive')->group(function () {
        Route::get('logout', [ExecutiveAuthController::class, 'logout']);
        Route::get('applications/filter-queries', [ApplicationController::class, 'getFilterQueries']);
        Route::apiResource('applications', ApplicationController::class)->except('store', 'delete');

        //support
        Route::get('support_conversations/{conversation}/resolve', [SupportConversationController::class, 'resolveConversation']);
        Route::post('support_conversations/{conversation}/send-message', [SupportConversationController::class, 'sendMessage']);
        Route::apiResource('support_conversations', SupportConversationController::class)->except('update');

        Route::prefix('dashboard')->group(function () {
            Route::get('get-overview', [AdminDashboardController::class, 'getOverview']);
            Route::get('get-application-stats', [AdminDashboardController::class, 'getApplicationStats']);
            Route::get('get-user-joining-stats', [AdminDashboardController::class, 'getUserJoiningStats']);
            Route::get('get-message-stats', [AdminDashboardController::class, 'getMessageStats']);
            Route::get('get-support-ticket-stats', [AdminDashboardController::class, 'getSupportTicketStats']);
            Route::get('get-subdivision-stats', [AdminDashboardController::class, 'getSubdivisionStats']);
        });
    });
});

Route::prefix('approver')->group(function () {
    Route::post('login', [ApproverAuthController::class, 'login']);

    Route::middleware('auth:api_approver')->group(function () {
        Route::get('logout', [ApproverAuthController::class, 'logout']);
        Route::get('applications/filter-queries', [ApplicationController::class, 'getFilterQueries']);
        Route::apiResource('applications', ApplicationController::class);
        Route::apiResource('messages', MessageController::class)->except('store', 'update');
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
        Route::apiResource('rtos', RTOController::class);
        Route::apiResource('users', UserController::class);
        Route::get('applications/filter-queries', [ApplicationController::class, 'getFilterQueries']);
        Route::apiResource('applications', ApplicationController::class)->except('store');
        Route::get('rto-applications/filter-queries', [RentToOwnApplicationController::class, 'getFilterQueries']);
        Route::apiResource('rto-applications', RentToOwnApplicationController::class);
        Route::apiResource('subdivisions', SubdivisionController::class);
        Route::apiResource('housing_models', HousingModelController::class);
        Route::apiResource('messages', MessageController::class)->except('store', 'update');
        Route::apiResource('photos', PhotoController::class);
        Route::apiResource('videos', VideoController::class);
        Route::apiResource('blogs', BlogController::class);

        Route::get('support_conversations/{conversation}/resolve', [SupportConversationController::class, 'resolveConversation']);
        Route::post('support_conversations/{conversation}/send-message', [SupportConversationController::class, 'sendMessage']);
        Route::apiResource('support_conversations', SupportConversationController::class)->except('update');

        Route::prefix('dashboard')->group(function () {
            Route::get('get-overview', [AdminDashboardController::class, 'getOverview']);
            Route::get('get-application-stats', [AdminDashboardController::class, 'getApplicationStats']);
            Route::get('get-user-joining-stats', [AdminDashboardController::class, 'getUserJoiningStats']);
            Route::get('get-message-stats', [AdminDashboardController::class, 'getMessageStats']);
            Route::get('get-support-ticket-stats', [AdminDashboardController::class, 'getSupportTicketStats']);
            Route::get('get-subdivision-stats', [AdminDashboardController::class, 'getSubdivisionStats']);
        });
    });

});

Route::prefix('rto')->group(function () {
    Route::post('login', [RTOAuthController::class, 'login']);
    Route::middleware('auth:api_rto')->group(function () {
        Route::get('logout', [RTOAuthController::class, 'logout']);

        Route::get('rto-applications/filter-queries', [RentToOwnApplicationController::class, 'getFilterQueries']);
        Route::apiResource('rto-applications', RentToOwnApplicationController::class);

        Route::prefix('dashboard')->group(function () {
            Route::get('get-overview', [AdminDashboardController::class, 'getOverview']);
            Route::get('get-application-stats', [AdminDashboardController::class, 'getApplicationStats']);
            Route::get('get-user-joining-stats', [AdminDashboardController::class, 'getUserJoiningStats']);
            Route::get('get-message-stats', [AdminDashboardController::class, 'getMessageStats']);
            Route::get('get-support-ticket-stats', [AdminDashboardController::class, 'getSupportTicketStats']);
            Route::get('get-subdivision-stats', [AdminDashboardController::class, 'getSubdivisionStats']);
        });
    });

});

