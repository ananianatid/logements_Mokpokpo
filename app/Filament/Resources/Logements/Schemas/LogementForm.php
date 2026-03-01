<?php

namespace App\Filament\Resources\Logements\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LogementForm
{
    public static function configure(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
            \Filament\Forms\Components\TextInput::make('numero_chambre')
            ->label('Numéro de Chambre')
            ->required(),

            \Filament\Forms\Components\Select::make('batiment_id')
            ->label('Bâtiment')
            ->relationship('batiment', 'nom')
            ->required(),

            \Filament\Forms\Components\Select::make('type_logement_id')
            ->label('Type de Logement')
            ->relationship('type_logement', 'nom')
            ->required(),

            \Filament\Forms\Components\Select::make('statut')
            ->options([
                'Disponible' => 'Disponible',
                'Réservé' => 'Réservé',
                'Occupé' => 'Occupé',
                'En maintenance' => 'En maintenance',
            ])
            ->required()
            ->default('Disponible'),

            \Filament\Forms\Components\TextInput::make('etage')
            ->label('Étage')
            ->numeric(),
        ]);
    }
}