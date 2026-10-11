<?php

namespace App\Console\Commands;

use App\Models\Siswa;
use Illuminate\Console\Command;

/**
 * Backfill satu kali: salin foto siswa yang sudah ada (siswas.foto_path)
 * ke avatar user (users.photo_path) agar langsung tampil di portal siswa.
 *
 * Idempoten — aman dijalankan berulang kali.
 */
class SyncSiswaFotoCommand extends Command
{
    protected $signature = 'siswa:sync-foto';

    protected $description = 'Sinkronkan foto siswa (siswas.foto_path) ke avatar user (users.photo_path)';

    public function handle(): int
    {
        $updated = 0;
        $checked = 0;

        Siswa::query()
            ->whereNotNull('user_id')
            ->with('user')
            ->chunkById(200, function ($siswaList) use (&$updated, &$checked): void {
                foreach ($siswaList as $siswa) {
                    $checked++;

                    $user = $siswa->user;

                    if (! $user) {
                        continue;
                    }

                    $target = $siswa->resolvedFotoPath();

                    if ($user->photo_path === $target) {
                        continue;
                    }

                    $user->forceFill(['photo_path' => $target])->saveQuietly();
                    $updated++;
                }
            });

        $this->info("Selesai. {$checked} siswa diperiksa, {$updated} avatar user diperbarui.");

        return self::SUCCESS;
    }
}
