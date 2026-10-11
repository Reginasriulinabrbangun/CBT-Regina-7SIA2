<?php

namespace App\Observers;

use App\Models\Siswa;

/**
 * Menjembatani foto siswa (siswas.foto_path) dengan avatar aplikasi (users.photo_path).
 *
 * Seluruh avatar — dashboard portal, Orchid, Filament, dan route /avatar/{user} —
 * membaca users.photo_path, sedangkan upload foto siswa dari panel admin disimpan
 * di siswas.foto_path. Observer ini menjaga keduanya tetap sinkron saat data ditulis,
 * sehingga accessor avatar tetap bebas query (tidak menimbulkan N+1).
 */
class SiswaObserver
{
    /**
     * Dipanggil setelah create maupun update.
     */
    public function saved(Siswa $siswa): void
    {
        $user = $siswa->user()->first();

        if (! $user) {
            return;
        }

        $target = $siswa->resolvedFotoPath();

        // saveQuietly: cukup memperbarui kolom, tanpa memicu event lain.
        if ($user->photo_path !== $target) {
            $user->forceFill(['photo_path' => $target])->saveQuietly();
        }
    }
}
