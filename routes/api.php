<?php

use App\Http\Controllers\OTPController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/get-otp/{mobile_no}',[OTPController::class,'getOtp'])->name('get-otp');
Route::get('/verify-otp/{otp}/{mobile_no}',[OTPController::class,'verifyOtp']);