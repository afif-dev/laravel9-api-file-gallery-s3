<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return redirect('/');
})->name('login');

// API Docs (swagger)
Route::prefix('docs')->group(function () {
    Route::get('/', function (Request $request) {
        return view('swagger-ui');
    })->middleware('slashes');

    Route::get('swagger.json', function () {
        $path = public_path() . "/swagger-ui-dist/swagger.json";
        return json_decode(file_get_contents($path), true); 
    });
});

// Reset Password 
Route::get('/reset-password/{token}', function ($token, Request $request) {
    return view('auth.reset-password', ['token' => $token, 'email' => $request->query('email') ]);
})->middleware('guest')->name('password.reset');