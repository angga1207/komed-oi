<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Middleware\AdminOnyMiddleware;
use App\Http\Middleware\ClientOnlyMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('Register', App\Livewire\Client\Register::class)->name('register');


Route::get('login', App\Livewire\Admin\Login::class)->name('login');
Route::get('logout', App\Livewire\Admin\Logout::class)->name('logout');

Route::middleware(['auth'])->group(function () {
    // Admin Panel
    Route::middleware([AdminOnyMiddleware::class])->group(function () {
        Route::get('dashboard', App\Livewire\Admin\Dashboard::class)->name('dashboard');

        Route::get('media/need-approval', App\Livewire\Admin\Media\NeedVerification::class)->name('media.need-approval');
        Route::get('media', App\Livewire\Admin\Media\Index::class)->name('media');

        // Users
        Route::get('users/user', App\Livewire\Admin\Users\User::class)->name('users.user');
        Route::get('users/admin', App\Livewire\Admin\Users\Admin::class)->name('users.admin');
    });

    // Client Panel
    Route::middleware([ClientOnlyMiddleware::class])->group(function () {
        Route::get('client-dashboard', App\Livewire\Client\Dashboard::class)->name('client-dashboard');
        Route::get('firstUpdateMedia', App\Livewire\Client\FirstUpdateMedia::class)->name('firstUpdateMedia');
    });

    // Impersonate
    Route::impersonate();
    Route::post('/updateFcmToken', [AuthController::class, 'updateFcmToken'])->name('updateFcmToken');
});
