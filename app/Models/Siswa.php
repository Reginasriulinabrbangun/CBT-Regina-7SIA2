<?php

namespace App\Models;

use App\Observers\SiswaObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([SiswaObserver::class])]
class Siswa extends Model
{
    protected $guarded = [];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Path foto siswa yang siap dipakai sebagai avatar.
     *
     * Menangani nilai string maupun array (Filament FileUpload) serta
     * string kosong, sehingga pemanggil selalu dapat null atau path valid.
     */
    public function resolvedFotoPath(): ?string
    {
        $path = $this->foto_path;

        if (is_array($path)) {
            $path = reset($path) ?: null;
        }

        $path = is_string($path) ? trim($path) : null;

        return $path === '' ? null : $path;
    }
}
