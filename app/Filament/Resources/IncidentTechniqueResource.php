<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IncidentTechniques\Pages\CreateIncidentTechnique;
use App\Filament\Resources\IncidentTechniques\Pages\EditIncidentTechnique;
use App\Filament\Resources\IncidentTechniques\Pages\ListIncidentTechniques;
use App\Filament\Resources\IncidentTechniques\Schemas\IncidentTechniqueForm;
use App\Filament\Resources\IncidentTechniques\Tables\IncidentTechniquesTable;
use App\Models\IncidentTechnique;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class IncidentTechniqueResource extends Resource
{
    protected static ?string $model = IncidentTechnique::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationLabel = 'Incidents Techniques';

    protected static ?string $pluralLabel = 'Incidents Techniques';

    protected static ?string $modelLabel = 'Incident Technique';

    protected static string|\UnitEnum|null $navigationGroup = 'Gestion Technique';

    public static function form(Schema $schema): Schema
    {
        return IncidentTechniqueForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IncidentTechniquesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListIncidentTechniques::route('/'),
            'create' => CreateIncidentTechnique::route('/create'),
            'edit' => EditIncidentTechnique::route('/{record}/edit'),
        ];
    }
}