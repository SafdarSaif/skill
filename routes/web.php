<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\PermissionController;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CategoryController;


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


