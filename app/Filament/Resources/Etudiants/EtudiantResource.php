<?php

namespace App\Filament\Resources\Etudiants;

use App\Filament\Resources\Etudiants\Pages\CreateEtudiant;
use App\Filament\Resources\Etudiants\Pages\EditEtudiant;
use App\Filament\Resources\Etudiants\Pages\ListEtudiants;
use App\Filament\Resources\Etudiants\Schemas\EtudiantForm;
use App\Filament\Resources\Etudiants\Tables\EtudiantsTable;
use App\Models\Etudiant;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EtudiantResource extends Resource
{
    protected static ?string $model = Etudiant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Schema $schema): Schema
    {
        return EtudiantForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EtudiantsTable::configure($table);
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
            'index' => ListEtudiants::route('/'),
            'create' => CreateEtudiant::route('/create'),
            'edit' => EditEtudiant::route('/{record}/edit'),
        ];
    }
}
