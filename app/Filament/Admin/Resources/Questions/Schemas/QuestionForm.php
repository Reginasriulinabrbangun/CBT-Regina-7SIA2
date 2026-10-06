<?php

namespace App\Filament\Admin\Resources\Questions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Repeater;

class QuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns()
                    ->columnSpanFull()
                    ->schema([
                        Select::make('subject_id')
                            ->relationship('subject', 'name')
                            ->label('Mata Pelajaran')
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Nama Mata Pelajaran')
                                    ->required()
                                    ->unique('subjects', 'name')

                            ])
                            ->native(false)
                            ->searchable()
                            ->preload(),



                        TextInput::make('score')
                            ->label('Bobot Nilai Soal')
                            ->required()
                            ->numeric()
                            ->default(1),

                        RichEditor::make('payload')
                            ->label('Deskripsi pertanyaan')
                            ->required()
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('soal-img')
                            ->columnSpanFull(),


                        Toggle::make('is_active')
                            ->label('Tersedia untuk ujian')
                            ->default(true)
                            ->required()
                    ]),
                //bagian pilihan jawaban
                Section::make()
                    ->columns()
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('answers')
                            ->label('Pilihan Jawaban')
                            ->addActionLabel('Tambah Pilihan baru')
                            ->relationship()
                            ->columns(4)
                            ->columnSpanFull()
                            ->reorderable()
                            ->schema([
                                TextInput::make('option')
                                    ->label('Teks Pilihan Jawaban')
                                    ->columnSpan(3)
                                    ->required(),

                                Toggle::make('is_correct')
                                    ->label('Jawaban Benar')
                                    ->inline(false),

                            ])
                    ]),


            ]);
    }
}
