<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;

/**
 * Single source of truth untuk avatar inisial Akun Pegawai.
 *
 * dipakai oleh LocalInitialsAvatarProvider (URL) dan
 * AvatarController (SVG response) agar konsisten.
 */
class InitialsAvatar
{
    /**
     * @var array<int, string>
     */
    public const PALETTE = [
        '#D97706', // amber-600
        '#059669', // emerald-600
        '#2563EB', // blue-600
        '#7C3AED', // violet-600
        '#DB2777', // pink-600
        '#4F46E5', // indigo-600
        '#0D9488', // teal-600
        '#EA580C', // orange-600
        '#0891B2', // cyan-600
    ];

    public static function displayNameFor(Model $record): string
    {
        return self::displayNameFromParts(
            $record->getAttribute('name'),
            $record->getAttribute('email')
        );
    }

    public static function displayNameFromParts(mixed $name, mixed $email = null): string
    {
        $name = trim((string) ($name ?? ''));

        if ($name !== '') {
            return $name;
        }

        $email = trim((string) ($email ?? ''));

        if ($email !== '') {
            $local = strstr($email.'@', '@', true) ?: $email;
            $local = trim(str_replace(['.', '_', '-'], ' ', (string) $local));

            if ($local !== '') {
                return $local;
            }
        }

        return 'U';
    }

    public static function initials(string $displayName): string
    {
        $parts = preg_split('/\s+/', trim($displayName)) ?: [];
        $parts = array_values(array_filter($parts, fn ($part) => $part !== ''));

        if (count($parts) >= 2) {
            return mb_strtoupper(mb_substr($parts[0], 0, 1).mb_substr($parts[1], 0, 1));
        }

        if (count($parts) === 1) {
            return mb_strtoupper(mb_substr($parts[0], 0, 2));
        }

        return 'U';
    }

    public static function background(string $displayName): string
    {
        $index = abs(crc32(mb_strtolower(trim($displayName)))) % count(self::PALETTE);

        return self::PALETTE[$index];
    }

    public static function svg(string $displayName): string
    {
        $initials = self::initials($displayName);
        $background = self::background($displayName);

        return sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 128 128">'
            .'<rect width="128" height="128" rx="64" fill="%s"/>'
            .'<text x="50%%" y="54%%" dominant-baseline="central" text-anchor="middle" '
            .'font-family="system-ui, -apple-system, Segoe UI, Roboto, sans-serif" '
            .'font-size="48" font-weight="600" fill="#FFFFFF">%s</text></svg>',
            $background,
            htmlspecialchars($initials, ENT_QUOTES, 'UTF-8')
        );
    }
}
