<?php

namespace App\Filament\Admin\Resources\Siswas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SiswasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto_path')
                    ->label('Foto')
                    ->circular()
                    ->disk('public'),
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nisn')
                    ->label('NISN')
                    ->searchable(),
                TextColumn::make('kelas')
                    ->label('Kelas')
                    ->badge()
                    ->color('gray')
                    ->searchable(),
                TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'L' ? 'Laki-laki' : 'Perempuan')
                    ->color(fn (string $state): string => $state === 'L' ? 'info' : 'danger'),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('kelas')
                    ->label('Kelas')
                    ->options(fn () => \App\Models\Siswa::query()->whereNotNull('kelas')->distinct()->pluck('kelas', 'kelas')->toArray()),
                SelectFilter::make('jenis_kelamin')
                    ->label('Jenis kelamin')
                    ->options(['L' => 'Laki-laki', 'P' => 'Perempuan']),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
