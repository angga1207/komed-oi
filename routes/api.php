<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/media/get', [RegisterController::class, 'getMedia']);
    Route::post('/media/update', [RegisterController::class, 'updateMedia']);
});
