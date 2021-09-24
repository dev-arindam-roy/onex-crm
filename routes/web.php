<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OnexController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('lang/{lang?}', [OnexController::class, 'locale'])->name('lang');
Route::name('onex.')->group(function () {
    Route::get('/', [OnexController::class, 'index'])->name('index');
});

