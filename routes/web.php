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

        Route::get('a/media-order', App\Livewire\Admin\MediaOrder\Index::class)->name('a.media-order');
        Route::get('a/media-order/create', App\Livewire\Admin\MediaOrder\Create::class)->name('a.media-order.create');
        Route::get('a/media-order/{order_code}', App\Livewire\Admin\MediaOrder\Detail::class)->name('a.media-order.detail');

        // Users
        Route::get('users/user', App\Livewire\Admin\Users\User::class)->name('users.user');
        Route::get('users/admin', App\Livewire\Admin\Users\Admin::class)->name('users.admin');
    });

    // Client Panel
    Route::middleware([ClientOnlyMiddleware::class])->group(function () {
        Route::get('client-dashboard', App\Livewire\Client\Dashboard::class)->name('client-dashboard');
        Route::get('firstUpdateMedia', App\Livewire\Client\FirstUpdateMedia::class)->name('firstUpdateMedia');

        Route::get('c/media-order', App\Livewire\Client\Media\Index::class)->name('media-order');
        Route::get('c/media-order/{order_code}', App\Livewire\Client\Media\Detail::class)->name('media-order.detail');
    });

    // Impersonate
    Route::impersonate();
    Route::post('/updateFcmToken', [AuthController::class, 'updateFcmToken'])->name('updateFcmToken');
});
