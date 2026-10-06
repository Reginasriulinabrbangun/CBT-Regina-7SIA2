<?php

use App\Http\Controllers\AvatarController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/avatar', [AvatarController::class, 'preview'])->name('avatar.preview');
Route::get('/avatar/{user}', [AvatarController::class, 'show'])->name('avatar.show');