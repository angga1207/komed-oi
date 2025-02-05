<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');

    return redirect()->route('dashboard');
});


Route::get('login', App\Livewire\Admin\Login::class)->name('login');
Route::get('logout', App\Livewire\Admin\Logout::class)->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', App\Livewire\Admin\Dashboard::class)->name('dashboard');

    Route::get('media/need-approval', App\Livewire\Admin\Media\NeedVerification::class)->name('media.need-approval');

    // Users
    Route::get('users/user', App\Livewire\Admin\Users\User::class)->name('users.user');
    Route::get('users/admin', App\Livewire\Admin\Users\Admin::class)->name('users.admin');

    // Impersonate
    Route::impersonate();
    Route::post('/updateFcmToken', [AuthController::class, 'updateFcmToken'])->name('updateFcmToken');
});
