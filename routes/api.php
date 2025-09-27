<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RoleController;

Route::post('/register', RegisterController::class);
Route::post('/login', LoginController::class);
Route::middleware('auth:sanctum')->post('/logout', LogoutController::class);

Route::post("/business", BusinessController::class);
Route::post("/products", ProductController::class);
Route::post("/product-variant", ProductVariantController::class);
Route::post("/roles", RoleController::class);
