<?php

namespace App\Filament\Admin\Resources\Siswas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class SiswaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Pribadi')
                    ->icon(Heroicon::OutlinedUser)
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        FileUpload::make('foto_path')
                            ->label('Foto siswa')
                            ->avatar()
                            ->image()
                            ->alignCenter()
                            ->columnSpanFull()
                            ->disk('public')
                            ->directory('siswa')
                            ->imageEditor()
                            ->deletable()
                            ->openable(),

                        TextInput::make('name')
                            ->label('Nama lengkap')
                            ->prefixIcon(Heroicon::OutlinedUser)
                            ->placeholder('Masukkan nama lengkap siswa')
                            ->maxLength(255)
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('nisn')
                            ->label('NISN')
                            ->placeholder('10 digit NISN')
                            ->helperText('Digunakan sebagai username login siswa')
                            ->maxLength(10)
                            ->required()
                            ->unique('siswas', 'nisn'),

                        TextInput::make('email')
                            ->label('Alamat email')
                            ->prefixIcon(Heroicon::OutlinedEnvelope)
                            ->placeholder('contoh@email.com')
                            ->email()
                            ->unique('siswas', 'email'),

                        Select::make('jenis_kelamin')
                            ->label('Jenis kelamin')
                            ->options(['L' => 'Laki-laki', 'P' => 'Perempuan'])
                            ->required(),

                        TextInput::make('tempat_lahir')
                            ->label('Tempat lahir')
                            ->placeholder('Kota kelahiran'),

                        DatePicker::make('tanggal_lahir')
                            ->label('Tanggal lahir'),

                        TextInput::make('no_hp')
                            ->label('No. HP')
                            ->prefixIcon(Heroicon::OutlinedPhone)
                            ->placeholder('08xxxxxxxxxx'),

                        Textarea::make('alamat')
                            ->label('Alamat')
                            ->placeholder('Alamat lengkap siswa')
                            ->columnSpanFull()
                            ->rows(3),
                    ]),

                Section::make('Data Akademik')
                    ->icon(Heroicon::OutlinedAcademicCap)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('kelas')
                            ->label('Kelas')
                            ->placeholder('mis. XII RPL 1'),
                    ]),

                Section::make('Akun Login')
                    ->icon(Heroicon::OutlinedKey)
                    ->columnSpanFull()
                    ->description('Akun dibuat otomatis saat menyimpan. Login memakai NISN dan kata sandi di bawah.')
                    ->schema([
                        TextInput::make('login_password')
                            ->label('Kata sandi akun')
                            ->password()
                            ->revealable()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated()
                            ->helperText('Kosongkan saat edit untuk mempertahankan kata sandi lama'),
                    ]),
            ]);
    }
}
