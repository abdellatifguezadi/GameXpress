<?php

use App\Http\Controllers\Api\V1\Admin\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\DashboardController;
use App\Http\Controllers\Api\V1\Admin\ProductController;
use App\Http\Controllers\Api\V1\Admin\CategoryController;
use App\Http\Controllers\Api\V1\Admin\UserController;
use App\Http\Controllers\Api\V1\Admin\ProductImageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Auth Routes
Route::post('V1/Admin/register', [AuthController::class, 'register'])->name('admin.register');
Route::post('V1/Admin/login', [AuthController::class, 'login'])->name('admin.login');

// Protected Routes
Route::middleware(['auth:sanctum'])
    ->prefix('V1/Admin')
    ->name('admin.')
    ->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])
            ->middleware('permission:view_dashboard')
            ->name('dashboard');

        // Products
        Route::apiResource('products', ProductController::class)->except('show');
        
        // Product Images
        Route::get('products/{product}/images', [ProductImageController::class, 'index'])->name('products.images.index');
        Route::post('products/{product}/images', [ProductImageController::class, 'store'])->name('products.images.store');
        Route::delete('products/{product}/images/{image}', [ProductImageController::class, 'destroy'])->name('products.images.destroy');
        Route::post('products/{product}/images/{image}/set-primary', [ProductImageController::class, 'setPrimary'])->name('products.images.setPrimary');

        // Categories
        // Route::apiResource('categories', CategoryController::class);

        // Users
        // Route::apiResource('users', UserController::class);
    });

Route::get('/user', function (Request $request) {
    return ['message' => 'You are authenticated'];
});

