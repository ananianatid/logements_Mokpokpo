<?php

namespace App\Filament\Resources\Batiments\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BatimentForm
{
    public static function configure(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                TextInput::make('nom')
                    ->required(),
                TextInput::make('adresse'),
                TextInput::make('nombre_etages')
                    ->numeric(),
            ]);
    }
}
