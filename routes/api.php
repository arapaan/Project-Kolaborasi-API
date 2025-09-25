<?php

use App\Http\Controllers\BusinessController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post("/business", BusinessController::class);
Route::post("/products", ProductController::class);
