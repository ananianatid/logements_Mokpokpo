<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RapportsDisciplinaires\Pages\CreateRapportDisciplinaire;
use App\Filament\Resources\RapportsDisciplinaires\Pages\EditRapportDisciplinaire;
use App\Filament\Resources\RapportsDisciplinaires\Pages\ListRapportsDisciplinaires;
use App\Filament\Resources\RapportsDisciplinaires\Schemas\RapportDisciplinaireForm;
use App\Filament\Resources\RapportsDisciplinaires\Tables\RapportsDisciplinairesTable;
use App\Models\RapportDisciplinaire;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RapportDisciplinaireResource extends Resource
{
    protected static ?string $model = RapportDisciplinaire::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-exclamation';

    protected static ?string $navigationLabel = 'Rapports Disciplinaires';

    protected static ?string $pluralLabel = 'Rapports Disciplinaires';

    protected static ?string $modelLabel = 'Rapport Disciplinaire';

    protected static string|\UnitEnum|null $navigationGroup = 'Gestion Disciplinaire';

    public static function form(Schema $schema): Schema
    {
        return RapportDisciplinaireForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RapportsDisciplinairesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRapportsDisciplinaires::route('/'),
            'create' => CreateRapportDisciplinaire::route('/create'),
            'edit' => EditRapportDisciplinaire::route('/{record}/edit'),
        ];
    }
}