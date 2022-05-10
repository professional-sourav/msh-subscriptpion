<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('tag', 'TagCrudController');
    Route::crud('user', 'UserCrudController');
    Route::crud('subscription', 'SubscriptionCrudController');
    Route::crud('site', 'SiteCrudController');
    Route::crud('plan', 'PlanCrudController');
    Route::get('plan/sync-with-stripe', 'PlanCrudController@syncWithStripe')->name('plan.sync-with-stripe');
}); // this should be the absolute last line of this file