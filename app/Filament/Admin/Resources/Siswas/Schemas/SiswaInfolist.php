<?php

namespace App\Filament\Admin\Resources\Siswas\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SiswaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profil Siswa')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        ImageEntry::make('foto_path')
                            ->hiddenLabel()
                            ->disk('public')
                            ->circular()
                            ->columnSpanFull()
                            ->alignCenter(),

                        TextEntry::make('name')
                            ->label('Nama lengkap'),

                        TextEntry::make('nisn')
                            ->label('NISN'),

                        TextEntry::make('kelas')
                            ->badge()
                            ->color('gray')
                            ->placeholder('-'),

                        TextEntry::make('email')
                            ->label('Email')
                            ->placeholder('-'),

                        TextEntry::make('jenis_kelamin')
                            ->label('Jenis kelamin')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => $state === 'L' ? 'Laki-laki' : 'Perempuan')
                            ->color(fn (string $state): string => $state === 'L' ? 'info' : 'danger'),

                        TextEntry::make('tempat_lahir')
                            ->label('Tempat lahir')
                            ->placeholder('-'),

                        TextEntry::make('tanggal_lahir')
                            ->label('Tanggal lahir')
                            ->date()
                            ->placeholder('-'),

                        TextEntry::make('no_hp')
                            ->label('No. HP')
                            ->placeholder('-'),

                        TextEntry::make('alamat')
                            ->label('Alamat')
                            ->columnSpanFull()
                            ->placeholder('-'),
                    ]),

                Section::make('Akun Login')
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('user.nisn')
                            ->label('Username (NISN)')
                            ->placeholder('-'),

                        TextEntry::make('created_at')
                            ->label('Terdaftar')
                            ->dateTime(),
                    ]),
            ]);
    }
}
