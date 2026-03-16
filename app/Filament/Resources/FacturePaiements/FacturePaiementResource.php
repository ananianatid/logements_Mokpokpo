<?php

namespace App\Filament\Resources\FacturePaiements;

use App\Filament\Resources\FacturePaiements\Pages\CreateFacturePaiement;
use App\Filament\Resources\FacturePaiements\Pages\EditFacturePaiement;
use App\Filament\Resources\FacturePaiements\Pages\ListFacturePaiements;
use App\Filament\Resources\FacturePaiements\Schemas\FacturePaiementForm;
use App\Filament\Resources\FacturePaiements\Tables\FacturePaiementsTable;
use App\Models\FacturePaiement;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FacturePaiementResource extends Resource
{
    protected static ?string $model = FacturePaiement::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return FacturePaiementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FacturePaiementsTable::configure($table);
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
            'index' => ListFacturePaiements::route('/'),
            'create' => CreateFacturePaiement::route('/create'),
            'edit' => EditFacturePaiement::route('/{record}/edit'),
        ];
    }
}
