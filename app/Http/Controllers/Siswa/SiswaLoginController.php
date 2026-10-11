<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class SiswaLoginController extends Controller
{
    public function show(): View
    {
        return view('siswa.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'nisn' => ['required', 'string', 'digits:10'],
            'password' => ['required', 'string'],
        ], [
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.digits' => 'NISN harus terdiri dari 10 digit angka.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $key = 'siswa-login:'.$credentials['nisn'].':'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return redirect()->route('siswa.login')
                ->withInput($request->only('nisn'))
                ->withErrors(['nisn' => 'Terlalu banyak percobaan masuk. Coba lagi dalam 1 menit.']);
        }

        $remember = $request->boolean('remember');

        if (! Auth::attempt(['nisn' => $credentials['nisn'], 'password' => $credentials['password']], $remember)) {
            RateLimiter::hit($key, 60);

            return redirect()->route('siswa.login')
                ->withInput($request->only('nisn'))
                ->withErrors(['nisn' => 'NISN atau kata sandi salah.']);
        }

        // Akun staff wajib lewat /admin/login, bukan portal siswa.
        if ($request->user()->is_staff) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('siswa.login')
                ->withInput($request->only('nisn'))
                ->withErrors(['nisn' => 'Akun ini akun petugas. Silakan masuk lewat halaman admin.']);
        }

        RateLimiter::clear($key);
        $request->session()->regenerate();

        // Portal siswa hanya punya satu halaman tujuan, jadi redirect eksplisit
        // (bukan intended) agar tidak ada intended basi yang ikut terbawa.
        $request->session()->forget('url.intended');

        return redirect()->route('siswa.dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('siswa.login');
    }
}
