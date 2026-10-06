<?php

namespace App\Filament\Admin\Resources\Siswas;

use App\Filament\Admin\Resources\Siswas\Pages\CreateSiswa;
use App\Filament\Admin\Resources\Siswas\Pages\EditSiswa;
use App\Filament\Admin\Resources\Siswas\Pages\ListSiswas;
use App\Filament\Admin\Resources\Siswas\Pages\ViewSiswa;
use App\Filament\Admin\Resources\Siswas\Schemas\SiswaForm;
use App\Filament\Admin\Resources\Siswas\Schemas\SiswaInfolist;
use App\Filament\Admin\Resources\Siswas\Tables\SiswasTable;
use App\Models\Siswa;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $navigationLabel = 'Siswa';

    protected static ?string $modelLabel = 'siswa';

    protected static ?string $slug = 'siswa';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return SiswaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SiswaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SiswasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSiswas::route('/'),
            'create' => CreateSiswa::route('/create'),
            'edit' => EditSiswa::route('/{record}/edit'),
            'view' => ViewSiswa::route('/{record}'),
        ];
    }
}
