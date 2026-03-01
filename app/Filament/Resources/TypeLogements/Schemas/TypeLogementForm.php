<?php

namespace App\Filament\Resources\TypeLogements\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TypeLogementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nom')
                    ->required(),
                Textarea::make('caracteristique')
                    ->columnSpanFull(),
                TextInput::make('prix')
                    ->required()
                    ->numeric(),
            ]);
    }
}
