<?php

namespace App\Filament\Resources\Handicaps;

use App\Filament\Resources\Handicaps\Pages\CreateHandicap;
use App\Filament\Resources\Handicaps\Pages\EditHandicap;
use App\Filament\Resources\Handicaps\Pages\ListHandicaps;
use App\Filament\Resources\Handicaps\Schemas\HandicapForm;
use App\Filament\Resources\Handicaps\Tables\HandicapsTable;
use App\Models\Handicap;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class HandicapResource extends Resource
{
    protected static ?string $model = Handicap::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-heart';

    protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Schema $schema): Schema
    {
        return HandicapForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HandicapsTable::configure($table);
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
            'index' => ListHandicaps::route('/'),
            'create' => CreateHandicap::route('/create'),
            'edit' => EditHandicap::route('/{record}/edit'),
        ];
    }
}
