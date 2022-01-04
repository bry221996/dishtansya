<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'api'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register',  [AuthController::class, 'register']);

    Route::post('order', [OrderController::class, 'store'])->middleware('auth:api');
});
