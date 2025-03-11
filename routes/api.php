<?php

use App\Http\Controllers\Api\V1\Admin\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/



Route::post('V1/Admin/register', [AuthController::class, 'register'])->name('admin.register');
Route::post('V1/Admin/login', [AuthController::class, 'login'])->name('admin.login');


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('V1/Admin/logout', [AuthController::class, 'logout'])->name('admin.logout');
});


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/user', function (Request $request) {
    return ['message' => 'You are authenticated'];
});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// });

// 