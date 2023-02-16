<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TokenGeneratorController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::apiResource('products', ProductController::class);
Route::middleware('auth:sanctum')->group(function() {
    Route::get('user', [UserController::class, 'show']);
});

Route::get('search', SearchController::class);
Route::post('/token/generator', TokenGeneratorController::class);
