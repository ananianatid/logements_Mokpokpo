<?php

namespace App\Filament\Resources\Batiments;

use App\Filament\Resources\Batiments\Pages\CreateBatiment;
use App\Filament\Resources\Batiments\Pages\EditBatiment;
use App\Filament\Resources\Batiments\Pages\ListBatiments;
use App\Filament\Resources\Batiments\Schemas\BatimentForm;
use App\Filament\Resources\Batiments\Tables\BatimentsTable;
use App\Models\Batiment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BatimentResource extends Resource
{
    protected static ?string $model = Batiment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Schema $schema): Schema
    {
        return BatimentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BatimentsTable::configure($table);
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
            'index' => ListBatiments::route('/'),
            'create' => CreateBatiment::route('/create'),
            'edit' => EditBatiment::route('/{record}/edit'),
        ];
    }
}
