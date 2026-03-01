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
                \Filament\Forms\Components\TextInput::make('nom')
                    ->required(),
                \Filament\Forms\Components\TextInput::make('adresse'),
                \Filament\Forms\Components\TextInput::make('nombre_etages')
                    ->numeric(),
            ]);
    }
}
