<?php

namespace App\Filament\AvatarProviders;

use App\Support\InitialsAvatar;
use Filament\AvatarProviders\Contracts\AvatarProvider;
use Illuminate\Database\Eloquent\Model;

/**
 * Avatar inisial offline-safe untuk Akun Pegawai via HTTP route.
 *
 * Mengembalikan URL biasa (/avatar/{user}) — bukan data-URI —
 * agar lolos CSP/img-src, ImageEntry/ImageColumn, dan topbar.
 */
class LocalInitialsAvatarProvider implements AvatarProvider
{
    public function get(Model $record): string
    {
        // Model sudah tersimpan: route spesifik user (cache-busting saat nama berubah).
        if ($record->exists && $record->getKey()) {
            $version = md5((string) $record->getAttribute('name').'|'.(string) $record->getAttribute('email').'|'.(string) $record->getAttribute('updated_at'));

            return route('avatar.show', $record).'?v='.substr($version, 0, 8);
        }

        // Model belum tersimpan (form create): preview via query param.
        $displayName = InitialsAvatar::displayNameFor($record);

        return url('/avatar?n='.urlencode($displayName));
    }

    /**
     * Inisial untuk fallback teks (badge non-gambar).
     */
    public function initialsFor(Model $record): string
    {
        return InitialsAvatar::initials(InitialsAvatar::displayNameFor($record));
    }
}
