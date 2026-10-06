<?php

namespace App\Filament\Admin\Resources\Siswas\Pages;

use App\Filament\Admin\Resources\Siswas\SiswaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSiswa extends EditRecord
{
    protected static string $resource = SiswaResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (! empty($data['login_password']) && $this->record->user) {
            $this->record->user->update(['password' => $data['login_password']]);
        }

        if ($this->record->user && ($data['name'] !== $this->record->user->name || $data['nisn'] !== $this->record->user->nisn)) {
            $this->record->user->update(['name' => $data['name'], 'nisn' => $data['nisn']]);
        }

        unset($data['login_password']);

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
