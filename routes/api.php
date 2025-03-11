<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    Route::prefix('admin')->group(function () {

        
        // Protected Routes
        Route::middleware(['auth:sanctum'])->group(function () {

        });
    });
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