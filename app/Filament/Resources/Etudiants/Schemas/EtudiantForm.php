<?php

namespace App\Filament\Resources\Etudiants\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EtudiantForm
{
    public static function configure(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
            \Filament\Forms\Components\TextInput::make('user_id')
            ->required()
            ->numeric(),
            \Filament\Forms\Components\TextInput::make('nom')
            ->required(),
            \Filament\Forms\Components\TextInput::make('prenom')
            ->required(),
            \Filament\Forms\Components\DatePicker::make('date_naissance')
            ->required(),
            \Filament\Forms\Components\TextInput::make('sexe')
            ->required(),
            \Filament\Forms\Components\DatePicker::make('date_obtention_bac'),
            \Filament\Forms\Components\TextInput::make('moyenne_bac')
            ->numeric()
            ->step(0.01)
            ->minValue(0)
            ->maxValue(20)
            ->label('Moyenne au Bac'),
            \Filament\Forms\Components\TextInput::make('adresse_actuelle'),
            \Filament\Forms\Components\TextInput::make('situation_matrimoniale'),
            \Filament\Forms\Components\Toggle::make('profil_complet')
            ->required(),
        ]);
    }
}