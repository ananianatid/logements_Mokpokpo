<?php

namespace App\Filament\Resources\Incidents\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class IncidentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('logement_id')
                    ->required()
                    ->numeric(),
                \Filament\Forms\Components\TextInput::make('signale_par_id')
                    ->required()
                    ->numeric(),
                \Filament\Forms\Components\TextInput::make('type')
                    ->required(),
                \Filament\Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                \Filament\Forms\Components\TextInput::make('statut')
                    ->required()
                    ->default('Nouveau'),
                \Filament\Forms\Components\DateTimePicker::make('date_signalement')
                    ->required(),
                \Filament\Forms\Components\TextInput::make('technicien_id')
                    ->numeric(),
                \Filament\Forms\Components\DateTimePicker::make('date_prise_en_charge'),
                \Filament\Forms\Components\DateTimePicker::make('date_resolution'),
                \Filament\Forms\Components\Textarea::make('rapport_intervention')
                    ->columnSpanFull(),
            ]);
    }
}
