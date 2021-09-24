<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administrator\SigninupController as AdminSigninupController;
use App\Http\Controllers\Administrator\DashboardController as AdminDashboardController;
use App\Http\Controllers\Administrator\ProfileController as AdminProfileController;

/*
|--------------------------------------------------------------------------
| Admins Routes
|--------------------------------------------------------------------------
|
*/

Route::prefix('administrator')->group(function () {
    Route::group(['middleware' => ['signinup:admin']], function () {
        Route::prefix('auth')->group(function () {
            Route::name('administrator.auth.')->group(function () {
                Route::get('/signin', [AdminSigninupController::class, 'login'])->name('signin');
                Route::post('/signin', [AdminSigninupController::class, 'loginProcess'])->name('signin.process');

                Route::get('/forgot-password', [AdminSigninupController::class, 'forgotPassword'])->name('forgot.password');
                Route::post('/forgot-password', [AdminSigninupController::class, 'forgotPasswordProcess'])->name('forgot.password.process');

                Route::get('/reset-password/{token}', [AdminSigninupController::class, 'resetPassword'])->name('reset.password');
                Route::post('/reset-password/{token}', [AdminSigninupController::class, 'resetPasswordSave'])->name('reset.password.save');

                Route::post('/sendVerifyEmail', [AdminSigninupController::class, 'sendVerifyEmail'])->name('verify.email.send');
                Route::get('/verify-email/{token}', [AdminSigninupController::class, 'verifyEmail'])->name('verify.email');
            });
        });
    });

    /** Post Signin Routes */
    Route::group(['middleware' => ['auth:admin']], function () {
        Route::prefix('account')->group(function () {
            Route::name('administrator.account.')->group(function () {
                Route::get('/', [AdminDashboardController::class, 'index'])->name('home');
                Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');
                Route::post('/logout', [AdminSigninupController::class, 'logout'])->name('logout');

                Route::get('/my-profile', [AdminProfileController::class, 'myProfile'])->name('myprofile');
                Route::post('/my-profile', [AdminProfileController::class, 'myProfileUpdate'])->name('myprofile.update');

                Route::get('/change-password', [AdminProfileController::class, 'changePassword'])->name('changepassword');
                Route::post('/change-password', [AdminProfileController::class, 'changePasswordSave'])->name('changepassword.save');

                Route::get('/change-email', [AdminProfileController::class, 'changeEmail'])->name('changeemail');
                Route::post('/change-email', [AdminProfileController::class, 'changeEmailSave'])->name('changeemail.save');
            });
        });
    });
});
