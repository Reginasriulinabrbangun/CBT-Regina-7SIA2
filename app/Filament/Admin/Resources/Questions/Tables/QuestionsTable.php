<?php

namespace App\Filament\Admin\Resources\Questions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use PhpParser\Node\Stmt\Label;

class QuestionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('no')
                    ->rowIndex()
                    ->width(40),
                TextColumn::make('subject.name')
                    ->searchable()
                    ->Label('Mata Pelajaran'),
                TextColumn::make('score')
                    ->numeric()
                    ->label('Bobot')
                    ->suffix(' poin')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jawaban')
                    ->state(
                        fn($record) => $record->answers->where('is_correct', true)->pluck('option')->count()
                    )->label('Jawaban Benar'),

                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('subject')
                    ->label('Mata Pelajaran')
                    ->relationship('subject', 'name')
                    ->multiple()
                    ->native(false),
                TernaryFilter::make('is_active')
                    ->label('Status Soal')
                    ->trueLabel('Tersedia untuk di ujian kan')
                    ->falseLabel('Tidak untuk di ujian kan')
                    ->placeholder('pilih salah satu')
                    ->native(false),

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
