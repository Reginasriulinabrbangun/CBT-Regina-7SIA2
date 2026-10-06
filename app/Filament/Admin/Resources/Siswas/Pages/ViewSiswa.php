<?php

namespace App\Filament\Admin\Resources\Siswas\Pages;

use App\Filament\Admin\Resources\Siswas\SiswaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSiswa extends ViewRecord
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
