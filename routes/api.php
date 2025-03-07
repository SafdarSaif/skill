<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OTPController;
use App\Http\Controllers\StudentPaymentController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EasebuzzPaymentController;

use App\Models\Students;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/get-otp/{mobile_no}',[OTPController::class,'getOtp'])->name('get-otp');
Route::get('/verify-otp/{otp}/{mobile_no}',[OTPController::class,'verifyOtp']);
Route::get('/students-details/{mobile_no}',[StudentsController::class,'getStudentDetails']);
Route::post('/students-registration',[StudentsController::class,'registerStudent']);
Route::get('/students-all-details/{mobile_no}',[StudentsController::class,'StudentAllDetaills']);
Route::get('/students-payment/{mobile_no}',[StudentPaymentController::class,'StudentPayment']);
Route::get('/pay-student-course-fee/{student_id}/{course_id}',[CourseController::class,'payStuCourseFee']);
Route::get('/all-categories',[CategoryController::class,'categories']);


Route::post('/pay', [EasebuzzPaymentController::class, 'initiatePayment'])->name('easebuzz.pay');
Route::post('/payment-success', [EasebuzzPaymentController::class, 'paymentSuccess'])->name('easebuzz.success');
Route::post('/payment-failure', [EasebuzzPaymentController::class, 'paymentFailure'])->name('easebuzz.failure');

