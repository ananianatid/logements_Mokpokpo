<?php

namespace App\Filament\Resources\EtatDesLieuxes;

use App\Filament\Resources\EtatDesLieuxes\Pages\CreateEtatDesLieux;
use App\Filament\Resources\EtatDesLieuxes\Pages\EditEtatDesLieux;
use App\Filament\Resources\EtatDesLieuxes\Pages\ListEtatDesLieuxes;
use App\Filament\Resources\EtatDesLieuxes\Schemas\EtatDesLieuxForm;
use App\Filament\Resources\EtatDesLieuxes\Tables\EtatDesLieuxesTable;
use App\Models\EtatDesLieux;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EtatDesLieuxResource extends Resource
{
    protected static ?string $model = EtatDesLieux::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return EtatDesLieuxForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EtatDesLieuxesTable::configure($table);
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
            'index' => ListEtatDesLieuxes::route('/'),
            'create' => CreateEtatDesLieux::route('/create'),
            'edit' => EditEtatDesLieux::route('/{record}/edit'),
        ];
    }
}
