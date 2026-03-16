<?php

namespace App\Filament\Resources\DemandeLogements;

use App\Filament\Resources\DemandeLogements\Pages\CreateDemandeLogement;
use App\Filament\Resources\DemandeLogements\Pages\EditDemandeLogement;
use App\Filament\Resources\DemandeLogements\Pages\ListDemandeLogements;
use App\Filament\Resources\DemandeLogements\Schemas\DemandeLogementForm;
use App\Filament\Resources\DemandeLogements\Tables\DemandeLogementsTable;
use App\Models\DemandeLogement;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DemandeLogementResource extends Resource
{
    protected static ?string $model = DemandeLogement::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-inbox-arrow-down';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return DemandeLogementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DemandeLogementsTable::configure($table);
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
            'index' => ListDemandeLogements::route('/'),
            'create' => CreateDemandeLogement::route('/create'),
            'edit' => EditDemandeLogement::route('/{record}/edit'),
        ];
    }
}
