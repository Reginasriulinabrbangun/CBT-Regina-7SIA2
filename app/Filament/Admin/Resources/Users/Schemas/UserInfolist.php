<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([

                        ImageEntry::make('user_avatar_url')
                            ->state(fn ($record) => $record->getFilamentAvatarUrl())
                            ->defaultImageUrl(fn ($record) => $record?->getFilamentAvatarUrl())
                            ->placeholder('-')
                            ->hiddenLabel()
                            ->alignCenter()
                            ->columnSpanFull()
                            ->circular(),

                        TextEntry::make('initials_fallback')
                            ->label('')
                            ->state(fn ($record) => app(\App\Filament\AvatarProviders\LocalInitialsAvatarProvider::class)->initialsFor($record))
                            ->badge()
                            ->alignCenter()
                            ->columnSpanFull(),

                        TextEntry::make('name'),

                        TextEntry::make('email')
                            ->label('Email address'),

                        TextEntry::make('email_verified_at')
                            ->dateTime()
                            ->placeholder('-'),

                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                    ])
            ]);
    }
}
