<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\FileGalleryController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,30');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,30');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->middleware('throttle:10,30');

Route::post('/forgot-password', [AuthController::class, 'forgetPassword'])->middleware('guest')->middleware('throttle:5,30');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('guest')->middleware('throttle:10,30');

Route::get('/profile', [AuthController::class, 'profile'])->middleware('auth:sanctum', 'throttle:10,1');

Route::resource('/file-gallery', FileGalleryController::class)->middleware('auth:sanctum');
