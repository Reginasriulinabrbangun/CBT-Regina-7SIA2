<?php

namespace App\Filament\Admin\Resources\Siswas\Pages;

use App\Filament\Admin\Resources\Siswas\SiswaResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateSiswa extends CreateRecord
{
    protected static string $resource = SiswaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = User::create([
            'name' => $data['name'],
            'nisn' => $data['nisn'],
            'email' => null,
            'password' => $data['login_password'],
            'is_staff' => false,
        ])->id;

        unset($data['login_password']);

        return $data;
    }
}
