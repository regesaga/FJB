<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SubCategoryController;
use App\Http\Controllers\Api\SubCategoryAttributeController;

// ========================================
// AUTENTIKASI API
// ========================================

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'not.suspended'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user(),
        ]);
    });
});

// ========================================
// KATALOG PUBLIK
// ========================================

Route::get('/categories', [CategoryController::class, 'index']);

Route::get(
    '/categories/{categoryId}/sub-categories',
    [SubCategoryController::class, 'index']
);

Route::get(
    '/sub-categories/{subCategoryId}/attributes',
    [SubCategoryAttributeController::class, 'index']
);