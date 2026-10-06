<?php

namespace App\Models;

use App\Filament\AvatarProviders\LocalInitialsAvatarProvider;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

#[Fillable(['name', 'email', 'nisn', 'password', 'photo_path', 'avatar_url', 'is_staff'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser, HasAvatar
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = ['user_avatar_url', 'avatar_url'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_staff' => 'boolean',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_staff;
    }

    /**
     * Get the user's avatar URL.
     *
     * Tidak pernah kosong: foto custom jika ada, kalau tidak
     * URL HTTP lokal /avatar/{user} (SVG inisial, offline-safe).
     */
    public function getUserAvatarUrlAttribute(): string
    {
        // 1. Check if custom photo exists in public disk
        if ($this->photo_path && Storage::disk('public')->exists($this->photo_path)) {
            return Storage::disk('public')->url($this->photo_path);
        }

        // 2. Fall back to local initials avatar via HTTP route (offline-safe, nama kosong ditangani)
        return app(LocalInitialsAvatarProvider::class)->get($this);
    }

    /**
     * Alias for viewing avatar_url in API/JSON/form payloads.
     */
    public function getAvatarUrlAttribute(): string
    {
        return $this->user_avatar_url;
    }

    /**
     * Alias for editing avatar_url by syncing it to photo_path.
     */
    public function setAvatarUrlAttribute(?string $value): void
    {
        $this->attributes['photo_path'] = $value;
    }

    /**
     * Filament HasAvatar implementation.
     */
    public function getFilamentAvatarUrl(): ?string
    {
        return $this->user_avatar_url;
    }
}