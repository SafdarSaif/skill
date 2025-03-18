<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\PermissionController;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StudentPaymentController;
use App\Http\Controllers\StudentCourseController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\NewsUpdateController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\StudentQueryController;
use App\Http\Controllers\CourseTypeController;
use App\Http\Controllers\SubjectVideoController;
use App\Http\Controllers\SubjectNoteController;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\TermsConditionController;


// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return view('content.index');
});

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('content.home');
    })->name('dashboard');
});



Route::view('/table', 'users.index')->name('table');

Route::group(['middleware' => ['auth']], function () {
    // Roles & Permissions
    Route::get('/users/permissions', [PermissionController::class, 'index'])->name('users.permissions');
    Route::get('/users/permissions/create', [PermissionController::class, 'create'])->name('users.permissions.create');
    Route::post('/users/permissions', [PermissionController::class, 'store'])->name('users.permissions');

    // Roles
    Route::get('/users/roles', [RoleController::class, 'index'])->name('users.roles');
    Route::get('/users/roles/create', [RoleController::class, 'create'])->name('users.roles.create');
    Route::post('/users/roles', [RoleController::class, 'store'])->name('users.roles');
    Route::get('/users/roles/edit/{id}', [RoleController::class, 'edit'])->name('users.roles.edit');
    Route::post('/users/roles/update', [RoleController::class, 'update'])->name('users.roles.update');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users');
});



Route::get('/student', [StudentsController::class, 'index'])->name('student');
Route::get('/student/create', [StudentsController::class, 'create'])->name('student.create');
Route::post('/student/store', [StudentsController::class, 'store'])->name('setting.student.store');
Route::get('/student/edit/{id}', [StudentsController::class, 'edit'])->name('student.edit');
Route::post('/student/update/{id}', [StudentsController::class, 'update'])->name('student.update');
Route::delete('/student/destroy/{id}', [StudentsController::class, 'destroy'])->name('student.destroy');
Route::get('student/status/{id}', [StudentsController::class, 'status'])->name('student.status');
Route::get('/student/{id}', [StudentsController::class, 'show'])->name('student.show');
// Route::post('/student', [StudentsController::class, 'show'])->name('student.show');


Route::get('/coursetype', [CourseTypeController::class, 'index'])->name('coursetype');
Route::get('/coursetype/create', [CourseTypeController::class, 'create'])->name('coursetype.create');
Route::post('/coursetype/store', [CourseTypeController::class, 'store'])->name('coursetype.store');
Route::get('/coursetype/edit/{id}', [CourseTypeController::class, 'edit'])->name('coursetype.edit');
Route::post('/coursetype/update/{id}', [CourseTypeController::class, 'update'])->name('coursetype.update');
Route::delete('/coursetype/destroy/{id}', [CourseTypeController::class, 'destroy'])->name('coursetype.destroy');
Route::get('coursetype/status/{id}', [CourseTypeController::class, 'status'])->name('coursetype.status');




Route::get('/course', [CourseController::class, 'index'])->name('course');
Route::get('/course/create', [CourseController::class, 'create'])->name('course.create');
Route::post('/course/store', [CourseController::class, 'store'])->name('course.store');
Route::get('/course/edit/{id}', [CourseController::class, 'edit'])->name('course.edit');
Route::post('/course/update/{id}', [CourseController::class, 'update'])->name('course.update');
Route::delete('/course/destroy/{id}', [CourseController::class, 'destroy'])->name('course.destroy');
Route::get('course/status/{id}', [CourseController::class, 'status'])->name('course.status');

Route::get('/category', [CategoryController::class, 'index'])->name('category');
Route::get('/category/create', [CategoryController::class, 'create'])->name('category.create');
Route::post('/category/store', [CategoryController::class, 'store'])->name('category.store');
Route::get('/category/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
Route::post('/category/update/{id}', [CategoryController::class, 'update'])->name('category.update');
Route::delete('/category/destroy/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
Route::get('category/status/{id}', [CategoryController::class, 'status'])->name('category.status');


Route::get('/payment', [StudentPaymentController::class, 'index'])->name('payment');
Route::get('/payment/create', [StudentPaymentController::class, 'create'])->name('payment.create');
Route::post('/payment/store', [StudentPaymentController::class, 'store'])->name('payment.store');
Route::get('/payment/edit/{id}', [StudentPaymentController::class, 'edit'])->name('payment.edit');
Route::post('/payment/update/{id}', [StudentPaymentController::class, 'update'])->name('payment.update');
Route::delete('/payment/destroy/{id}', [StudentPaymentController::class, 'destroy'])->name('payment.destroy');
Route::get('payment/status/{id}', [StudentPaymentController::class, 'status'])->name('payment.status');

Route::get('/studentcourse', [StudentCourseController::class, 'index'])->name('studentcourse');
Route::get('/studentcourse/create', [StudentCourseController::class, 'create'])->name('studentcourse.create');
Route::post('/studentcourse/store', [StudentCourseController::class, 'store'])->name('studentcourse.store');
Route::get('/studentcourse/edit/{id}', [StudentCourseController::class, 'edit'])->name('studentcourse.edit');
Route::post('/studentcourse/update/{id}', [StudentCourseController::class, 'update'])->name('studentcourse.update');
Route::delete('/studentcourse/destroy/{id}', [StudentCourseController::class, 'destroy'])->name('studentcourse.destroy');
Route::get('studentcourse/status/{id}', [StudentCourseController::class, 'status'])->name('studentcourse.status');

Route::get('/subject', [SubjectController::class, 'index'])->name('subject');
Route::get('/subject/create', [SubjectController::class, 'create'])->name('subject.create');
Route::post('/subject/store', [SubjectController::class, 'store'])->name('subject.store');
Route::get('/subject/edit/{id}', [SubjectController::class, 'edit'])->name('subject.edit');
Route::post('/subject/update/{id}', [SubjectController::class, 'update'])->name('subject.update');
Route::delete('/subject/destroy/{id}', [SubjectController::class, 'destroy'])->name('subject.destroy');
Route::get('subject/status/{id}', [SubjectController::class, 'status'])->name('subject.status');



Route::get('/news', [NewsUpdateController::class, 'index'])->name('news');
Route::get('/news/create', [NewsUpdateController::class, 'create'])->name('news.create');
Route::post('/news/store', [NewsUpdateController::class, 'store'])->name('news.store');
Route::get('/news/edit/{id}', [NewsUpdateController::class, 'edit'])->name('news.edit');
Route::post('/news/update/{id}', [NewsUpdateController::class, 'update'])->name('news.update');
Route::delete('/news/destroy/{id}', [NewsUpdateController::class, 'destroy'])->name('news.destroy');
Route::get('news/status/{id}', [NewsUpdateController::class, 'status'])->name('news.status');


Route::get('/slider', [SliderController::class, 'index'])->name('slider');
Route::get('/slider/create', [SliderController::class, 'create'])->name('slider.create');
Route::post('/slider/store', [SliderController::class, 'store'])->name('slider.store');
Route::get('/slider/edit/{id}', [SliderController::class, 'edit'])->name('slider.edit');
Route::post('/slider/update/{id}', [SliderController::class, 'update'])->name('slider.update');
Route::delete('/slider/destroy/{id}', [SliderController::class, 'destroy'])->name('slider.destroy');
Route::get('slider/status/{id}', [SliderController::class, 'status'])->name('slider.status');


Route::get('/faq', [FaqController::class, 'index'])->name('faq');
Route::get('/faq/create', [FaqController::class, 'create'])->name('faq.create');
Route::post('/faq/store', [FaqController::class, 'store'])->name('faq.store');
Route::get('/faq/edit/{id}', [FaqController::class, 'edit'])->name('faq.edit');
Route::post('/faq/update/{id}', [FaqController::class, 'update'])->name('faq.update');
Route::delete('/faq/destroy/{id}', [FaqController::class, 'destroy'])->name('faq.destroy');
Route::get('faq/status/{id}', [FaqController::class, 'status'])->name('faq.status');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
// Route::get('/contact/create', [ContactController::class, 'create'])->name('contact.create');
// Route::post('/contact/store', [ContactController::class, 'store'])->name('contact.store');
// Route::get('/contact/edit/{id}', [ContactController::class, 'edit'])->name('contact.edit');
// Route::post('/contact/update/{id}', [ContactController::class, 'update'])->name('contact.update');
// Route::delete('/contact/destroy/{id}', [ContactController::class, 'destroy'])->name('contact.destroy');
// Route::get('contact/status/{id}', [ContactController::class, 'status'])->name('contact.status');



Route::get('/studentquery', [StudentQueryController::class, 'index'])->name('studentquery');
Route::get('/studentquery/create', [StudentQueryController::class, 'create'])->name('studentquery.create');
Route::post('/studentquery/store', [StudentQueryController::class, 'store'])->name('studentquery.store');
Route::get('/studentquery/edit/{id}', [StudentQueryController::class, 'edit'])->name('studentquery.edit');
Route::post('/studentquery/update/{id}', [StudentQueryController::class, 'update'])->name('studentquery.update');
Route::delete('/studentquery/destroy/{id}', [StudentQueryController::class, 'destroy'])->name('studentquery.destroy');
Route::get('studentquery/status/{id}', [StudentQueryController::class, 'status'])->name('studentquery.status');


Route::get('/subjectvideo', [SubjectVideoController::class, 'index'])->name('subjectvideo');
Route::get('/subjectvideo/create', [SubjectVideoController::class, 'create'])->name('subjectvideo.create');
Route::post('/subjectvideo/store', [SubjectVideoController::class, 'store'])->name('subjectvideo.store');
Route::get('/subjectvideo/edit/{id}', [SubjectVideoController::class, 'edit'])->name('subjectvideo.edit');
Route::post('/subjectvideo/update/{id}', [SubjectVideoController::class, 'update'])->name('subjectvideo.update');
Route::delete('/subjectvideo/destroy/{id}', [SubjectVideoController::class, 'destroy'])->name('subjectvideo.destroy');
Route::get('subjectvideo/status/{id}', [SubjectVideoController::class, 'status'])->name('subjectvideo.status');

Route::get('/subjectvideo/{id}', [SubjectVideoController::class, 'show'])->name('subjectvideo.show');



Route::get('/subjectnote', [SubjectNoteController::class, 'index'])->name('subjectnote');
Route::get('/subjectnote/create', [SubjectNoteController::class, 'create'])->name('subjectnote.create');
Route::post('/subjectnote/store', [SubjectNoteController::class, 'store'])->name('subjectnote.store');
Route::get('/subjectnote/edit/{id}', [SubjectNoteController::class, 'edit'])->name('subjectnote.edit');
Route::post('/subjectnote/update/{id}', [SubjectNoteController::class, 'update'])->name('subjectnote.update');
Route::delete('/subjectnote/destroy/{id}', [SubjectNoteController::class, 'destroy'])->name('subjectnote.destroy');
Route::get('subjectnote/status/{id}', [SubjectNoteController::class, 'status'])->name('subjectnote.status');



Route::get('/ebook', [EbookController::class, 'index'])->name('ebook');
Route::get('/ebook/create', [EbookController::class, 'create'])->name('ebook.create');
Route::post('/ebook/store', [EbookController::class, 'store'])->name('ebook.store');
Route::get('/ebook/edit/{id}', [EbookController::class, 'edit'])->name('ebook.edit');
Route::post('/ebook/update/{id}', [EbookController::class, 'update'])->name('ebook.update');
Route::delete('/ebook/destroy/{id}', [EbookController::class, 'destroy'])->name('ebook.destroy');
Route::get('ebook/status/{id}', [EbookController::class, 'status'])->name('ebook.status');


// Term and Condition
Route::get('/term', [TermsConditionController::class, 'index'])->name('term');
Route::get('/term/create', [TermsConditionController::class, 'create'])->name('term.create');
Route::post('/term/store', [TermsConditionController::class, 'store'])->name('term.store');
Route::get('/term/edit/{id}', [TermsConditionController::class, 'edit'])->name('term.edit');
Route::post('/term/update/{id}', [TermsConditionController::class, 'update'])->name('term.update');
Route::delete('/term/destroy/{id}', [TermsConditionController::class, 'destroy'])->name('term.destroy');
Route::get('term/status/{id}', [TermsConditionController::class, 'status'])->name('term.status');



// Privacy Policy
Route::get('/privacy', [PrivacyPolicyController::class, 'index'])->name('privacy');
Route::get('/privacy/create', [PrivacyPolicyController::class, 'create'])->name('privacy.create');
Route::post('/privacy/store', [PrivacyPolicyController::class, 'store'])->name('privacy.store');
Route::get('/privacy/edit/{id}', [PrivacyPolicyController::class, 'edit'])->name('privacy.edit');
Route::post('/privacy/update/{id}', [PrivacyPolicyController::class, 'update'])->name('privacy.update');
Route::delete('/privacy/destroy/{id}', [PrivacyPolicyController::class, 'destroy'])->name('privacy.destroy');
Route::get('privacy/status/{id}', [PrivacyPolicyController::class, 'status'])->name('privacy.status');