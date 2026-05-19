<?php

use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware('admin')->group(function () {

        Route::apiResource('users', UserManagementController::class);

        Route::apiResource('departments', DepartmentController::class);
    });
});
