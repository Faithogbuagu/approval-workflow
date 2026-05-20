<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\Admin\ApprovalHierarchyController;
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

        Route::apiResource('approval-hierarchies', ApprovalHierarchyController::class);
    });

        Route::apiResource('requests', RequestController::class);

        Route::get('requests/all', [RequestController::class, 'getAllRequests']);
        Route::get('requests/pending', [RequestController::class, 'getPendingRequest']);
        Route::get('requests/{request}', [RequestController::class, 'show']);
        Route::post('requests/{approval}/approve', [ApprovalController::class, 'approve']);
        Route::post('requests/{approval}/reject', [ApprovalController::class, 'reject']);
});
