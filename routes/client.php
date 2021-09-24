<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Client\SignupController as ClientSignupController;

/*
|--------------------------------------------------------------------------
| Clients Routes
|--------------------------------------------------------------------------
|
*/

Route::prefix('client')->group(function () {
    Route::group(['middleware' => ['signinup']], function () {
        Route::prefix('auth')->group(function () {
            Route::name('client.auth.')->group(function () {
                /* Signup Routes */
                Route::get('/signup', [ClientSignupController::class, 'index'])->name('signup');
                Route::post('/signup', [ClientSignupController::class, 'initialSignup'])->name('signup.save');
                
                Route::get('/signup/email-verification/{token}', [ClientSignupController::class, 'emailVerification'])->name('signup.emailverification');
                
                Route::post('/signup/resend-email', [ClientSignupController::class, 'resendEmailVerification'])->name('signup.resendEmail');
                Route::get('/signup/resend-email', [ClientSignupController::class, 'resendEmailVerificationSuccess'])->name('signup.resendEmailSuccess');
                
                Route::get('/signup/step-2', [ClientSignupController::class, 'signupStepTwo'])->name('signup.stepTwo');
                Route::post('/signup/step-2', [ClientSignupController::class, 'signupStepTwoSave'])->name('signup.stepTwo.save');
                
                Route::get('/signup/step-3', [ClientSignupController::class, 'signupStepThree'])->name('signup.stepThree');
                Route::post('/signup/step-3', [ClientSignupController::class, 'signupStepThreeSave'])->name('signup.stepThree.save');

                Route::get('/signup/step-4', [ClientSignupController::class, 'signupStepFour'])->name('signup.stepFour');
                Route::post('/signup/step-4', [ClientSignupController::class, 'signupStepFourSave'])->name('signup.stepFour.save');
                
                /* Signin Routes */
                Route::get('/signin', [ClientSignupController::class, 'login'])->name('signin');
                Route::post('/signin', [ClientSignupController::class, 'loginProcess'])->name('signin.process');

                /* Forgot Password Routes */
                Route::get('/forgot-password', [ClientSignupController::class, 'forgotPassword'])->name('forgot.password');
                Route::post('/forgot-password', [ClientSignupController::class, 'forgotPasswordProcess'])->name('forgot.password.process');
                Route::get('/reset-password/{token}', [ClientSignupController::class, 'resetPassword'])->name('forgot.password.reset');
                Route::post('/reset-password/{token}', [ClientSignupController::class, 'resetPasswordSave'])->name('forgot.password.reset.save');
            });
        });
    });
    
    /* Account Routes */
    Route::group(['middleware' => ['auth']], function () {
        Route::prefix('account')->group(function () {
            Route::name('client.account.')->group(function () {
                Route::get('/', [ClientDashboardController::class, 'index'])->name('home');
                Route::get('/dashboard', [ClientDashboardController::class, 'dashboard'])->name('dashboard');
                Route::post('/logout', [ClientDashboardController::class, 'logout'])->name('logout');
            });
        });
    });
});
