<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RoleController;

Route::post('/register', RegisterController::class);
Route::post('/login', LoginController::class);
Route::middleware('auth:sanctum')->post('/logout', LogoutController::class);
Route::post("/category", CategoryController::class);
Route::get("/categories", [CategoryController::class, "index"]);

Route::get("/business/{name}", [BusinessController::class, 'show']);
Route::post("/products", ProductController::class);
Route::get("/products/{category}", [ProductController::class, 'show']);
Route::post("/product-variant", ProductVariantController::class);
Route::get("/product-variant/{product}", [ProductVariantController::class, "show"]);
Route::post("/roles", RoleController::class);
Route::post("/orders", OrderController::class);
Route::get('/sales-per-month', [OrderController::class, 'salesPerMonth']);
Route::post("/notifications", NotificationController::class);
Route::post("/discounts", DiscountController::class);
Route::get('/pages', [PageController::class, 'index']);
Route::get('/pages/{slug}', [PageController::class, 'show']);
