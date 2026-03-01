<?php

namespace App\Filament\Resources\ContratHabitations;

use App\Filament\Resources\ContratHabitations\Pages\CreateContratHabitation;
use App\Filament\Resources\ContratHabitations\Pages\EditContratHabitation;
use App\Filament\Resources\ContratHabitations\Pages\ListContratHabitations;
use App\Filament\Resources\ContratHabitations\Schemas\ContratHabitationForm;
use App\Filament\Resources\ContratHabitations\Tables\ContratHabitationsTable;
use App\Models\ContratHabitation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ContratHabitationResource extends Resource
{
    protected static ?string $model = ContratHabitation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return ContratHabitationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContratHabitationsTable::configure($table);
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
            'index' => ListContratHabitations::route('/'),
            'create' => CreateContratHabitation::route('/create'),
            'edit' => EditContratHabitation::route('/{record}/edit'),
        ];
    }
}
