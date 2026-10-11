<?php

use App\Http\Controllers\AvatarController;
use App\Http\Controllers\Siswa\SiswaLoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/avatar', [AvatarController::class, 'preview'])->name('avatar.preview');
Route::get('/avatar/{user}', [AvatarController::class, 'show'])->name('avatar.show');

// Portal siswa (login memakai NISN, tampilan Orchid; dashboard via Orchid Screen)
Route::middleware('guest')->group(function () {
    Route::get('/siswa/login', [SiswaLoginController::class, 'show'])->name('siswa.login');
    Route::post('/siswa/login', [SiswaLoginController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('siswa.login.store');

    // Alias bawaan Laravel agar middleware `auth` bisa redirect ke sini.
    Route::get('/login', fn () => redirect()->route('siswa.login'))->name('login');
});

Route::middleware(['auth', 'siswa'])->group(function () {
    Route::get('/dashboard', fn () => redirect()->route('siswa.dashboard'))->name('dashboard');
    // URI /siswa/keluar agar tidak tabrakan dengan POST logout bawaan Orchid.
    Route::match(['get', 'post'], '/siswa/keluar', [SiswaLoginController::class, 'destroy'])->name('siswa.logout');
});
