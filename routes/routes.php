<?php

use Andreytech\PaypalWebhook\Controllers\PaypalController;
use Illuminate\Support\Facades\Route;

Route::get('subscription/create', [PaypalController::class, 'createSubscription']);
Route::any('paypal/webhook', [PaypalController::class, 'webhook']);
Route::get('subscriptions/list', [PaypalController::class, 'getSubscriptions']);

