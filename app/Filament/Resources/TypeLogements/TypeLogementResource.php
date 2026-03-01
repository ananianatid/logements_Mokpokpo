<?php

namespace App\Filament\Resources\TypeLogements;

use App\Filament\Resources\TypeLogements\Pages\CreateTypeLogement;
use App\Filament\Resources\TypeLogements\Pages\EditTypeLogement;
use App\Filament\Resources\TypeLogements\Pages\ListTypeLogements;
use App\Filament\Resources\TypeLogements\Schemas\TypeLogementForm;
use App\Filament\Resources\TypeLogements\Tables\TypeLogementsTable;
use App\Models\TypeLogement;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TypeLogementResource extends Resource
{
    protected static ?string $model = TypeLogement::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Schema $schema): Schema
    {
        return TypeLogementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TypeLogementsTable::configure($table);
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
            'index' => ListTypeLogements::route('/'),
            'create' => CreateTypeLogement::route('/create'),
            'edit' => EditTypeLogement::route('/{record}/edit'),
        ];
    }
}
