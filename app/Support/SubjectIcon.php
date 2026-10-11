<?php

namespace App\Support;

/**
 * Resolver ikon mata pelajaran.
 *
 * Memetakan nama mapel dari tabel `subjects` ke file ikon di
 * public/images/subjects/. Pencocokan dilakukan persis lebih dulu,
 * lalu sebagian (agar "Matematika Wajib" tetap dapat ikon), dan
 * akhirnya jatuh ke ikon default.
 */
class SubjectIcon
{
    public const DEFAULT_FILE = 'default';

    /**
     * Pencocokan persis (nama mapel huruf kecil) => nama file tanpa ekstensi.
     *
     * @var array<string, string>
     */
    protected const MAP = [
        'matematika' => 'matematika',
        'bahasa indonesia' => 'bahasa-indonesia',
        'fisika' => 'fisika',
        'informatika' => 'informatika',
        'sejarah indonesia' => 'sejarah-indonesia',
        'kimia' => 'kimia',
        'geografi' => 'geografi',
    ];

    /**
     * Pencocokan sebagian, untuk nama yang lebih panjang/spesifik.
     *
     * @var array<string, string>
     */
    protected const CONTAINS = [
        'matematika' => 'matematika',
        'bahasa indonesia' => 'bahasa-indonesia',
        'fisika' => 'fisika',
        'informatika' => 'informatika',
        'sejarah' => 'sejarah-indonesia',
        'kimia' => 'kimia',
        'geografi' => 'geografi',
    ];

    /**
     * Nama file ikon (tanpa ekstensi) untuk sebuah mapel.
     */
    public static function file(?string $name): string
    {
        $key = strtolower(trim((string) $name));
        $key = (string) preg_replace('/\s+/', ' ', $key);

        if ($key === '') {
            return self::DEFAULT_FILE;
        }

        if (isset(self::MAP[$key])) {
            return self::MAP[$key];
        }

        foreach (self::CONTAINS as $needle => $file) {
            if (str_contains($key, $needle)) {
                return $file;
            }
        }

        return self::DEFAULT_FILE;
    }

    /**
     * URL ikon untuk sebuah mapel.
     */
    public static function path(?string $name): string
    {
        return asset('images/subjects/'.self::file($name).'.svg');
    }
}
