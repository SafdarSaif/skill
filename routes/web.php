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



Route::get('/studentquery', [StudentQueryController::class, 'index'])->name('studentquery');
Route::get('/studentquery/create', [StudentQueryController::class, 'create'])->name('studentquery.create');
Route::post('/studentquery/store', [StudentQueryController::class, 'store'])->name('studentquery.store');
Route::get('/studentquery/edit/{id}', [StudentQueryController::class, 'edit'])->name('studentquery.edit');
Route::post('/studentquery/update/{id}', [StudentQueryController::class, 'update'])->name('studentquery.update');
Route::delete('/studentquery/destroy/{id}', [StudentQueryController::class, 'destroy'])->name('studentquery.destroy');
Route::get('studentquery/status/{id}', [StudentQueryController::class, 'status'])->name('studentquery.status');