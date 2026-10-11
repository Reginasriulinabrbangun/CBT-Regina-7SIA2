<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCanonicalHost
{
    /**
     * Paksa satu host kanonis (dari config app.url) untuk semua request web.
     *
     * Latar: cookie sesi terikat pada host. Jika user membuka situs lewat
     * alias host lain (mis. localhost vs 127.0.0.1), redirect absolut bawaan
     * Laravel (route(), intended()) akan melompat host tanpa membawa cookie
     * sesi. Gejala khas: login pertama "mental" kembali ke halaman login
     * tanpa error, login kedua (sudah di host kanonis) berhasil.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $appHost = parse_url((string) config('app.url'), PHP_URL_HOST);

        if (! $appHost || $request->is('up')) {
            return $next($request);
        }

        if (strtolower($request->getHost()) !== strtolower($appHost)) {
            $target = rtrim((string) config('app.url'), '/').$request->getRequestUri();

            return redirect()->away($target, 301);
        }

        return $next($request);
    }
}
