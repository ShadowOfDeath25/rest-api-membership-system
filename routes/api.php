<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function () {
    return request()->user();
});
Route::apiResource("plans", PlanController::class)->middleware('auth:sanctum');
Route::apiResource("subscriptions", SubscriptionController::class)->middleware('auth:sanctum');


// Auth Routes
Route::post("login", AuthController::class . "@login")->name("login");
Route::post("register", AuthController::class . "@register")->name("register");
Route::post("logout", AuthController::class . "@logout")->middleware('auth:sanctum')->name("logout");

