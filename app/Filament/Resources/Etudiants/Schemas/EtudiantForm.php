<?php

namespace App\Filament\Resources\Etudiants\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use App\Models\DistancePrefecture;

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
            \Filament\Forms\Components\TextInput::make('annee_obtention_bac')
            ->numeric()
            ->minValue(1900)
            ->maxValue(date('Y') + 1),
            \Filament\Forms\Components\TextInput::make('moyenne_bac')
            ->numeric()
            ->step(0.01)
            ->minValue(0)
            ->maxValue(20)
            ->label('Moyenne au Bac'),
            Select::make('prefecture_origine')
            ->label('Préfecture d\'origine')
            ->options(function () {
            $grouped = [];
            foreach (DistancePrefecture::orderBy('region')->orderBy('prefecture')->get() as $row) {
                $grouped[$row->region][$row->prefecture] = $row->prefecture;
            }
            return $grouped;
        })
            ->searchable()
            ->nullable(),
            \Filament\Forms\Components\TextInput::make('situation_matrimoniale'),
            \Filament\Forms\Components\Toggle::make('profil_complet')
            ->required(),
        ]);
    }
}