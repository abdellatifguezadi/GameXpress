<?php

use App\Http\Controllers\Api\V1\Admin\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\DashboardController;
use App\Http\Controllers\Api\V1\Admin\ProductController;
use App\Http\Controllers\Api\V1\Admin\CategoryController;
use App\Http\Controllers\Api\V1\Admin\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/



Route::post('V1/Admin/register', [AuthController::class, 'register'])->name('admin.register');
Route::post('V1/Admin/login', [AuthController::class, 'login'])->name('admin.login');


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('V1/Admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

    // Dashboard
    Route::get('V1/Admin/dashboard', [DashboardController::class, 'index'])
        ->middleware('permission:view_dashboard')
        ->name('admin.dashboard');

    // Products
    Route::get('V1/Admin/products', [ProductController::class, 'index'])
        ->middleware('permission:view_products')
        ->name('admin.products.index');
    
    Route::post('V1/Admin/products', [ProductController::class, 'store'])
        ->middleware('permission:create_products')
        ->name('admin.products.store');
    
    Route::put('V1/Admin/products/{product}', [ProductController::class, 'update'])
        ->middleware('permission:edit_products')
        ->name('admin.products.update');
    
    Route::delete('V1/Admin/products/{product}', [ProductController::class, 'destroy'])
        ->middleware('permission:delete_products')
        ->name('admin.products.destroy');

    // Categories
    Route::get('V1/Admin/categories', [CategoryController::class, 'index'])
        ->middleware('permission:view_categories')
        ->name('admin.categories.index');
    
    Route::post('V1/Admin/categories', [CategoryController::class, 'store'])
        ->middleware('permission:create_categories')
        ->name('admin.categories.store');
    
    Route::put('V1/Admin/categories/{category}', [CategoryController::class, 'update'])
        ->middleware('permission:edit_categories')
        ->name('admin.categories.update');
    
    Route::delete('V1/Admin/categories/{category}', [CategoryController::class, 'destroy'])
        ->middleware('permission:delete_categories')
        ->name('admin.categories.destroy');

    // Users
    Route::get('V1/Admin/users', [UserController::class, 'index'])
        ->middleware('permission:view_users')
        ->name('admin.users.index');
    
    Route::post('V1/Admin/users', [UserController::class, 'store'])
        ->middleware('permission:create_users')
        ->name('admin.users.store');
    
    Route::put('V1/Admin/users/{user}', [UserController::class, 'update'])
        ->middleware('permission:edit_users')
        ->name('admin.users.update');
    
    Route::delete('V1/Admin/users/{user}', [UserController::class, 'destroy'])
        ->middleware('permission:delete_users')
        ->name('admin.users.destroy');
});



Route::get('/user', function (Request $request) {
    return ['message' => 'You are authenticated'];
});

