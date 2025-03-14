<?php

use App\Http\Controllers\Api\V1\Admin\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\DashboardController;
use App\Http\Controllers\Api\V1\Admin\ProductController;
use App\Http\Controllers\Api\V1\Admin\CategoryController;
use App\Http\Controllers\Api\V1\Admin\UserController;
use App\Http\Controllers\Api\V1\Admin\ProductImageController;
use App\Http\Controllers\Api\V1\Admin\RoleController;

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
            ->middleware('role:super_admin|product_manager|user_manager')
            ->name('dashboard');

        // Products
        // Route::apiResource('products', ProductController::class)->except('show');
        Route::get('products', [ProductController::class, 'index'])->middleware('role:super_admin|product_manager')->name('products.index');
        Route::post('products', [ProductController::class, 'store'])->middleware('role:super_admin|product_manager')->name('products.store');
        Route::put('products/{product}', [ProductController::class, 'update'])->middleware('role:super_admin|product_manager')->name('products.update');
        Route::delete('products/{product}', [ProductController::class, 'destroy'])->middleware('role:super_admin|product_manager')->name('products.destroy');
        Route::get('products/{product}', [ProductController::class, 'show'])->middleware('role:super_admin|product_manager')->name('products.show');
        Route::post('products/{product}/restore', [ProductController::class, 'restore'])->middleware('role:super_admin|product_manager')->name('products.restore');

        // Product Images

        Route::post('products/{product}/images/{image}/set-primary', [ProductImageController::class, 'setPrimary'])->middleware('role:super_admin|product_manager')->name('products.images.set_primary');

        // Categories
        // Route::apiResource('categories', CategoryController::class);
        Route::get('categories', [CategoryController::class, 'index'])->middleware('role:super_admin|product_manager')->name('categories.index');
        Route::post('categories', [CategoryController::class, 'store'])->middleware('role:super_admin|product_manager')->name('categories.store');
        Route::put('categories/{category}', [CategoryController::class, 'update'])->middleware('role:super_admin|product_manager')->name('categories.update');
        Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->middleware('role:super_admin|product_manager')->name('categories.destroy');
        Route::get('categories/{category}', [CategoryController::class, 'show'])->middleware('role:super_admin|product_manager')->name('categories.show');


        // Users
        // Route::apiResource('users', UserController::class);
        Route::get('users', [UserController::class, 'index'])->middleware('role:super_admin|user_manager')->name('users.index');
        Route::post('users', [UserController::class, 'store'])->middleware('role:super_admin|user_manager')->name('users.store');
        Route::put('users/{user}', [UserController::class, 'update'])->middleware('role:super_admin|user_manager')->name('users.update');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->middleware('role:super_admin|user_manager')->name('users.destroy');
        Route::get('users/{user}', [UserController::class, 'show'])->middleware('role:super_admin|user_manager')->name('users.show');
        Route::post('users/{user}/restore', [UserController::class, 'restore'])->middleware('role:super_admin|user_manager')->name('users.restore');
        Route::put('users/{user}/role', [UserController::class, 'updateRole'])->middleware('role:super_admin')->name('users.update-role');

        // Roles routes
        Route::get('roles', [RoleController::class, 'index'])
            ->middleware('role:super_admin')
            ->name('roles.index');
    });

Route::get('/user', function (Request $request) {
    return ['message' => 'You are authenticated'];
});
