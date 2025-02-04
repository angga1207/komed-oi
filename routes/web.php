<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');

    return redirect()->route('dashboard');
});


Route::get('login', App\Livewire\Admin\Login::class)->name('login');
Route::get('logout', App\Livewire\Admin\Logout::class)->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', App\Livewire\Admin\Dashboard::class)->name('dashboard');

    // Impersonate
    Route::impersonate();
});
