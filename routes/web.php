<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::group(['namespace' => 'Subscriptions'], function() {
    Route::middleware(['auth'])->group(function () {
        Route::get('/plans', [App\Http\Controllers\Subscriptions\SubscriptionController::class, 'index'])->name('plans');
        Route::get('/payments', [App\Http\Controllers\Subscriptions\PaymentController::class, 'index'])->name('payments');
        Route::post('/payments', [App\Http\Controllers\Subscriptions\PaymentController::class, 'store'])->name('payments.store');
        Route::get('/subscription/canceled', [App\Http\Controllers\Subscriptions\SubscriptionController::class, 'canceled'])->name('subscription.canceled');
        Route::get('/subscription/cancel', [App\Http\Controllers\Subscriptions\SubscriptionController::class, 'cancel'])->name('subscription.cancel');
        Route::get('/subscription/success', [App\Http\Controllers\Subscriptions\SubscriptionController::class, 'success'])->name('subscription.success');
    });
});

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
