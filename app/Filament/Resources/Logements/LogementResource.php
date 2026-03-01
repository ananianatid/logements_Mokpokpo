<?php

namespace App\Filament\Resources\Logements;

use App\Filament\Resources\Logements\Pages\CreateLogement;
use App\Filament\Resources\Logements\Pages\EditLogement;
use App\Filament\Resources\Logements\Pages\ListLogements;
use App\Filament\Resources\Logements\Schemas\LogementForm;
use App\Filament\Resources\Logements\Tables\LogementsTable;
use App\Models\Logement;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LogementResource extends Resource
{
    protected static ?string $model = Logement::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'numero_chambre';

    public static function form(Schema $schema): Schema
    {
        return LogementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LogementsTable::configure($table);
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
            'index' => ListLogements::route('/'),
            'create' => CreateLogement::route('/create'),
            'edit' => EditLogement::route('/{record}/edit'),
        ];
    }
}
