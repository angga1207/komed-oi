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
        Route::get('media/{unique_id}', App\Livewire\Admin\Media\Detail::class)->name('media.detail');
        Route::get('media/{unique_id}/edit', App\Livewire\Admin\Media\Edit::class)->name('media.edit');

        Route::get('a/media-orderr', App\Livewire\Admin\MediaOrder\Indexx::class)->name('a.media-order.indexx');

        Route::get('a/media-order', App\Livewire\Admin\MediaOrder\Index::class)->name('a.media-order');
        Route::get('a/media-order/agenda', App\Livewire\Admin\MediaOrder\Agenda::class)->name('a.media-order.agenda');
        Route::get('a/media-order/create', App\Livewire\Admin\MediaOrder\Create::class)->name('a.media-order.create');
        Route::get('a/media-order/create_manual', App\Livewire\Admin\MediaOrder\CreateManual::class)->name('a.media-order.create_manual');
        Route::get('a/media-order/{order_code}', App\Livewire\Admin\MediaOrder\Detail::class)->name('a.media-order.detail');

        Route::get('a/kontrak-media', App\Livewire\Admin\MediaKontrak\Index::class)->name('a.media-kontrak');
        Route::get('a/kontrak-media/{unique_id}', App\Livewire\Admin\MediaKontrak\Detail::class)->name('a.media-kontrak.detail');
        Route::get('a/kontrak-media/{unique_id}/chart', App\Livewire\Admin\MediaKontrak\Chart::class)->name('a.media-kontrak.detail.chart');

        Route::get('announcements', App\Livewire\Admin\Announcements\Index::class)->name('announcements');

        // Dev
        Route::get('dev', App\Livewire\Admin\Dev\Index::class)->name('dev.index');
        Route::get('dev/import-media', App\Livewire\Admin\Dev\ImportMedia::class)->name('dev.import-media');

        // Users
        Route::get('users/user', App\Livewire\Admin\Users\User::class)->name('users.user');
        Route::get('users/admin', App\Livewire\Admin\Users\Admin::class)->name('users.admin');
    });

    // Client Panel
    Route::middleware([ClientOnlyMiddleware::class])->group(function () {
        Route::get('client-dashboard', App\Livewire\Client\Dashboard::class)->name('client-dashboard');
        Route::get('firstUpdateMedia', App\Livewire\Client\FirstUpdateMedia::class)->name('firstUpdateMedia');

        Route::get('c/media-order', App\Livewire\Client\Media\Index::class)->name('media-order');
        Route::get('c/media-order/report', App\Livewire\Client\Media\Report::class)->name('media-order.report');
        Route::get('c/media-order/{order_code}', App\Livewire\Client\Media\Detail::class)->name('media-order.detail');

        Route::get('c/profile', App\Livewire\Client\Profile\Index::class)->name('client.profile');
    });

    Route::get('/me', App\Livewire\Admin\Me::class)->name('me');

    // Impersonate
    Route::impersonate();
    Route::post('/updateFcmToken', [AuthController::class, 'updateFcmToken'])->name('updateFcmToken');
});
