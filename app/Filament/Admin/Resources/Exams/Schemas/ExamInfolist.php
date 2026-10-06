<?php

namespace App\Filament\Admin\Resources\Exams\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ExamInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('min_score')
                    ->numeric(),
                TextEntry::make('duration')
                    ->numeric(),
                TextEntry::make('conduct_at')
                    ->dateTime(),
                IconEntry::make('exact_time')
                    ->boolean(),
                TextEntry::make('expired_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
