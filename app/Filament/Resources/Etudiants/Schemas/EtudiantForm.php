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
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('nom')
                    ->required(),
                TextInput::make('prenom')
                    ->required(),
                DatePicker::make('date_naissance')
                    ->required(),
                TextInput::make('sexe')
                    ->required(),
                DatePicker::make('date_obtention_bac'),
                TextInput::make('adresse_actuelle'),
                TextInput::make('situation_matrimoniale'),
                Toggle::make('profil_complet')
                    ->required(),
            ]);
    }
}
