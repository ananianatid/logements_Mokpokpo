<?php

namespace App\Filament\Resources\Handicaps\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class HandicapForm
{
    public static function configure(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('nom')
                    ->required(),
                \Filament\Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
