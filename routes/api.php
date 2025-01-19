<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/example', function(){
    return response()->json([
        'message' => "Hello api"
    ]);
});

Route::get('/test-db', function () {
    try {
        DB::connection()->getPdo();
        return response()->json(['success' => 'Database connected successfully']);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
});

Route::post("/register",[AuthController::class, 'userCreateMethod'])->name('user_register');
Route::post("/login", [AuthController::class, 'userLoginMethod'])->name('user_login');

// Protected Routes which require bearer token
Route::middleware('auth:sanctum')->group(function(){
    Route::post("/profile", [UserController::class, 'userDetailMethod'])->name('user_detail');

    Route::middleware('admin')->group(function(){
        Route::post('/allusers', [AdminController::class, 'viewAllUsersMethod'])->name('view_all_users');
    });
});
