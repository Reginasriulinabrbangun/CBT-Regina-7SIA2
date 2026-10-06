<?php

namespace App\Filament\Admin\Resources\Questions\Schemas;

use Dom\Text;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;
use PhpParser\Node\Stmt\Label;
use Random\Engine\Secure;

class QuestionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(4)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('subject.name')
                            ->columnSpan(2)
                            ->label('Mata Pelajaran')
                            ->placeholder('-'),
                        TextEntry::make('score')
                            ->numeric()
                            ->label('Bobot Nilai ')
                            ->suffix(' poin'),
                        IconEntry::make('is_active')
                            ->boolean()
                            ->label('Tersedia untuk ujian'),
                        TextEntry::make('payload')
                            ->label('Soal')
                            ->html()
                            ->size(TextSize::Medium)
                            ->columnSpanFull(),
                        TextEntry::make('answers.option')
                            ->hiddenLabel()
                            ->listWithLineBreaks()
                            ->bulleted()
                            ->columnSpanFull(),
                        TextEntry::make('correctS')
                            ->label('Jawaban Benar')
                            ->state(
                                fn($record) => $record->answers->where('is_correct', true)->pluck('option')
                            )->badge()->color('success')->size(TextSize::Medium)
                            ->columnSpanFull(),
                    ])
            ]);
    }
}
