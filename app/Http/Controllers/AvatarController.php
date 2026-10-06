<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\InitialsAvatar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class AvatarController extends Controller
{
    /**
     * GET /avatar/{user} — avatar pegawai via HTTP (anti-CSP, cacheable).
     *
     * Kalau punya foto custom: redirect ke file storage.
     * Kalau tidak: render SVG inisial lokal.
     */
    public function show(User $user): Response
    {
        if ($user->photo_path && Storage::disk('public')->exists($user->photo_path)) {
            return redirect(Storage::disk('public')->url($user->photo_path));
        }

        return $this->svgResponse(InitialsAvatar::displayNameFor($user));
    }

    /**
     * GET /avatar?n=Nama — preview untuk user yang belum tersimpan (form create).
     */
    public function preview(Request $request): Response
    {
        $name = (string) $request->query('n', 'U');

        return $this->svgResponse(InitialsAvatar::displayNameFromParts($name));
    }

    protected function svgResponse(string $displayName): Response
    {
        return response(InitialsAvatar::svg($displayName), 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
