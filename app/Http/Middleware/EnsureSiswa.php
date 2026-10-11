<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSiswa
{
    /**
     * Hanya user non-staff (siswa) yang boleh lanjut.
     * Staff diarahkan ke panel admin Filament.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->is_staff) {
            if (! $user) {
                return redirect()->route('siswa.login');
            }

            return redirect()->route('filament.admin.auth.login');
        }

        return $next($request);
    }
}
