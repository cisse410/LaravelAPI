<?php

use App\Http\Controllers\Api\Auth\LoginRegisterController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(LoginRegisterController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});
Route::controller(ProductController::class)->group(function () {
    Route::post('/products', 'index');
    Route::get('/products/{product}', 'show');
    Route::get('products/search/{name}', 'search');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LoginRegisterController::class, 'logout']);
    Route::controller(ProductController::class)->group(function () {
        Route::post('/products', 'store');
        Route::put('/products/{product}', 'update');
        Route::delete('/products/{product}', 'destroy');
    });
});