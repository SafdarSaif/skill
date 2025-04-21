<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OTPController;
use App\Http\Controllers\StudentPaymentController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EasebuzzPaymentController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\NewsUpdateController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\TermsConditionController;
use App\Http\Controllers\StudentProgressController;
use App\Http\Controllers\StudentQueryController;
// use App\Http\Controllers\EasebuzzController;
use App\Http\Controllers\NewsReadController;

use App\Models\Students;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/get-otp/{mobile_no}', [OTPController::class, 'getOtp'])->name('get-otp');
Route::get('/verify-otp/{otp}/{mobile_no}', [OTPController::class, 'verifyOtp']);
Route::get('/resend-otp/{mobile_no}', [OTPController::class, 'reSendOtp']);
// API modification for Students Progress
Route::get('/students-details/{mobile_no}', [StudentsController::class, 'getStudentDetails']);
Route::post('/students-registration', [StudentsController::class, 'registerStudent']);
Route::get('/students-all-details/{mobile_no}', [StudentsController::class, 'StudentAllDetaills']);
Route::get('/students-payment/{mobile_no}', [StudentPaymentController::class, 'StudentPayment']);
Route::get('/pay-student-course-fee/{student_id}/{course_id}', [CourseController::class, 'payStuCourseFee']);
Route::get('/all-categories', [CategoryController::class, 'categories']);
Route::get('/all-courses/{column}/{value}', [CourseController::class, 'coursesFunc']);
// Route::get('/all-courses',[CourseController::class,'coursesFunc']);
Route::post('/category-courses', [CategoryController::class, 'getCategoryCourses']);
Route::get('/course-wise-subjects', [SubjectController::class, 'getCourseSubjects']);
Route::get('/course-wise-subjects/{column}/{value}', [SubjectController::class, 'getCourseSubjects']);
Route::post('/student-update', [StudentsController::class, 'updateStudents']);

// API for Content
Route::get('/all-faq', [FaqController::class, 'getFaqs'])->name('all-faq');
Route::get('/all-news', [NewsUpdateController::class, 'getNew'])->name('all-news');
Route::post('/news-read', [NewsReadController::class, 'markAsRead'])->name('news-read');


Route::get('/subect-wise-content/{subject_id}/{student_id}', [SubjectController::class, 'getVideoNotesBySubject']);
Route::get('/get-privacy', [PrivacyPolicyController::class, 'getPrivacy'])->name('get-privacy');
Route::get('/get-terms', [TermsConditionController::class, 'getTerms'])->name('get-terms');

Route::post('/pay', [EasebuzzPaymentController::class, 'initiatePayment'])->name('easebuzz.pay');
Route::post('/payment-success', [EasebuzzPaymentController::class, 'paymentSuccess'])->name('easebuzz.success');
Route::post('/payment-failure', [EasebuzzPaymentController::class, 'paymentFailure'])->name('easebuzz.failure');

Route::get('/easebuzz/initiate', [EasebuzzPaymentController::class, 'initiate_payment_show']);
Route::post('/easebuzz/initiate', [EasebuzzPaymentController::class, 'initiate_payment_ebz']);
Route::post('/easebuzz/response', [EasebuzzPaymentController::class, 'ebz_response']);
Route::post('/easebuzz/success', [EasebuzzPaymentController::class, 'payment_success']);
Route::post('/easebuzz/failure', [EasebuzzPaymentController::class, 'payment_failure']);



Route::post('/update-progress', [StudentProgressController::class, 'updateProgress'])->name('update-progress');
Route::get('/get-progress', [StudentProgressController::class, 'getProgress'])->name('get-progress');
Route::get('type-wise-course', [CourseController::class, 'getCourseByType']);
Route::get('type-wise-course/{course_type_id}', [CourseController::class, 'getCourseByType']);

Route::post('/get-query', [StudentQueryController::class, 'getQuery'])->name('get-query');
Route::get('/return-response/{student_id}/{video_id}', [StudentQueryController::class, 'sndResponse'])->name('return-response');

Route::get('/get-student-progress/{student_id}/{subject_id}/{video_id?}', [StudentProgressController::class, 'getStudentProgress'])->name('get-student-progress');
Route::get('/get-contact', [ContactController::class, 'getContact'])->name('get-contact');

Route::get('/get-contact', [ContactController::class, 'getContact'])->name('get-contact');

Route::post('/fee-receipt', [StudentPaymentController::class, 'generateFeeReceipt'])->name('fee-receipt');





// API modification for Frontend

Route::get('/banner-courses', [CourseController::class, 'bannerCoursesFunc']);
Route::get('/all-courses', [CourseController::class, 'coursesFunc']);
