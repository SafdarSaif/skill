<?php

use Illuminate\Support\Facades\Route;

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

// Route::group(['middleware' => ['auth']], function () {
//     // Roles & Permissions
//     Route::get('/users/permissions', [PermissionController::class, 'index'])->name('users.permissions');
//     Route::get('/users/permissions/create', [PermissionController::class, 'create'])->name('users.permissions.create');
//     Route::post('/users/permissions', [PermissionController::class, 'store'])->name('users.permissions');

//     // Roles
//     Route::get('/users/roles', [RoleController::class, 'index'])->name('users.roles');
//     Route::get('/users/roles/create', [RoleController::class, 'create'])->name('users.roles.create');
//     Route::post('/users/roles', [RoleController::class, 'store'])->name('users.roles');
//     Route::get('/users/roles/edit/{id}', [RoleController::class, 'edit'])->name('users.roles.edit');
//     Route::post('/users/roles/update', [RoleController::class, 'update'])->name('users.roles.update');

//     // Users
//     Route::get('/users', [UserController::class, 'index'])->name('users');
//     Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
//     Route::post('/users', [UserController::class, 'store'])->name('users');

    // Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
    // Route::post('/users/update', [UserController::class, 'update'])->name('users.update');
    // Route::get('/users/delete/{id}', [UserController::class, 'destroy'])->name('users.delete');

   




    //Fresh Apply (Basic Detail)
    // Route::get('/basic/admission-session', [ApplicationsController::class, 'admissionsession'])->name('admission.application');



// });
