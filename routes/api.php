<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OTPController;
use App\Http\Controllers\StudentPaymentController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EasebuzzPaymentController;
use App\Http\Controllers\SubjectController;
// use App\Http\Controllers\EasebuzzController;

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
Route::get('/all-courses/{column}/{value}',[CourseController::class,'coursesFunc']);
Route::get('/all-courses',[CourseController::class,'coursesFunc']);
Route::post('/category-courses',[CategoryController::class,'getCategoryCourses']);
Route::get('/course-wise-subjects', [SubjectController::class,'getCourseSubjects']);
Route::post('/student-update', [StudentsController::class,'updateStudents']);


Route::post('/pay', [EasebuzzPaymentController::class, 'initiatePayment'])->name('easebuzz.pay');
Route::post('/payment-success', [EasebuzzPaymentController::class, 'paymentSuccess'])->name('easebuzz.success');
Route::post('/payment-failure', [EasebuzzPaymentController::class, 'paymentFailure'])->name('easebuzz.failure');

Route::get('/easebuzz/initiate', [EasebuzzPaymentController::class, 'initiate_payment_show']);
Route::post('/easebuzz/initiate', [EasebuzzPaymentController::class, 'initiate_payment_ebz']);
Route::post('/easebuzz/response', [EasebuzzPaymentController::class, 'ebz_response']);
Route::post('/easebuzz/success', [EasebuzzPaymentController::class, 'payment_success']);
Route::post('/easebuzz/failure', [EasebuzzPaymentController::class, 'payment_failure']);