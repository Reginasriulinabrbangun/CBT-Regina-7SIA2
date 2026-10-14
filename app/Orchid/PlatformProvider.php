<?php

declare(strict_types=1);

namespace App\Orchid;

use App\Models\Exam;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;
use Orchid\Platform\Dashboard;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param  Dashboard  $dashboard
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // Aset statis di /public (css & js tema) tidak dikirim dengan header cache oleh
        // `artisan serve`, sehingga browser memakai cache heuristik dan bisa terus memakai
        // versi lama. Tambahkan query versi dari mtime agar setiap perubahan langsung dipakai.
        $resources = config('platform.resource', []);

        foreach (['stylesheets', 'scripts'] as $type) {
            $resources[$type] = array_map(
                fn ($path) => $this->versionedAsset((string) $path),
                $resources[$type] ?? []
            );
        }

        config(['platform.resource' => $resources]);
    }

    /**
     * Tambahkan query `?v=<mtime>` pada aset lokal (URL eksternal dibiarkan apa adanya).
     */
    protected function versionedAsset(string $path): string
    {
        if (! str_starts_with($path, '/')) {
            return $path;
        }

        $file = public_path(ltrim((string) strtok($path, '?'), '/'));

        return is_file($file) ? $path.'?v='.filemtime($file) : $path;
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        $aktif = (int) Exam::query()
            ->where('conduct_at', '<=', now())
            ->where(fn ($q) => $q->whereNull('expired_at')->orWhere('expired_at', '>=', now()))
            ->count();

        return [
            Menu::make('Dashboard')
                ->icon('bs.speedometer2')
                ->title('Portal Siswa')
                ->route('siswa.dashboard')
                ->permission('siswa.dashboard'),

            Menu::make('Jadwal Ujian')
                ->icon('bs.calendar3')
                ->url('#')
                ->badge(fn () => 'Segera', Color::BASIC),

            Menu::make('Ujian Aktif')
                ->icon('bs.lightning-charge')
                ->url('#')
                ->badge(fn () => $aktif, Color::SUCCESS),

            Menu::make('Riwayat Ujian')
                ->icon('bs.clock-history')
                ->url('#')
                ->badge(fn () => 'Segera', Color::BASIC),

            Menu::make('Hasil dan Nilai')
                ->icon('bs.award')
                ->url('#')
                ->badge(fn () => 'Segera', Color::BASIC),

            Menu::make('Profil Siswa')
                ->icon('bs.person')
                ->url('#')
                ->badge(fn () => 'Segera', Color::BASIC)
                ->divider(),

            Menu::make('Pusat Bantuan')
                ->icon('bs.headset')
                ->url('#'),

            Menu::make('Keluar')
                ->icon('bs.box-arrow-right')
                ->route('siswa.logout'),
        ];
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group('Portal Siswa')
                ->addPermission('siswa.dashboard', 'Dashboard siswa'),

            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
        ];
    }
}
