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
        // Route::apiResource('products', ProductController::class)->except('show');
        Route::get('products', [ProductController::class, 'index'])->middleware('permission:view_products')->name('products.index');
        Route::post('products', [ProductController::class, 'store'])->middleware('permission:create_products')->name('products.store');
        Route::put('products/{product}', [ProductController::class, 'update'])->middleware('permission:edit_products')->name('products.update');
        Route::delete('products/{product}', [ProductController::class, 'destroy'])->middleware('permission:delete_products')->name('products.destroy');
        Route::get('products/{product}', [ProductController::class, 'show'])->middleware('permission:view_products')->name('products.show');

        // Product Images
        Route::get('products/{product}/images', [ProductImageController::class, 'index'])->middleware('permission:view_products')->name('products.images.index');
        Route::post('products/{product}/images', [ProductImageController::class, 'store'])->middleware('permission:edit_products')->name('products.images.store');
        Route::delete('products/{product}/images/{image}', [ProductImageController::class, 'destroy'])->middleware('permission:edit_products')->name('products.images.destroy');
        Route::post('products/{product}/images/{image}/set-primary', [ProductImageController::class, 'setPrimary'])->middleware('permission:edit_products')->name('products.images.set_primary');

        // Categories
        // Route::apiResource('categories', CategoryController::class);
        Route::get('categories', [CategoryController::class, 'index'])->middleware('permission:view_categories')->name('categories.index');
        Route::post('categories', [CategoryController::class, 'store'])->middleware('permission:create_categories')->name('categories.store');
        Route::put('categories/{category}', [CategoryController::class, 'update'])->middleware('permission:edit_categories')->name('categories.update');
        Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->middleware('permission:delete_categories')->name('categories.destroy');
        Route::get('categories/{category}', [CategoryController::class, 'show'])->middleware('permission:view_categories')->name('categories.show');
        

        // Users
        // Route::apiResource('users', UserController::class);
    });

Route::get('/user', function (Request $request) {
    return ['message' => 'You are authenticated'];
});
