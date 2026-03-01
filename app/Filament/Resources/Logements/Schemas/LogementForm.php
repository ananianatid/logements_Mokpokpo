<?php

namespace App\Filament\Resources\Logements\Schemas;

use Filament\Forms\Components\Select;

class LogementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
            TextInput::make('numero_chambre')
            ->label('Numéro de Chambre')
            ->required(),

            Select::make('batiment_id')
            ->label('Bâtiment')
            ->relationship('batiment', 'nom')
            ->required(),

            Select::make('type_logement_id')
            ->label('Type de Logement')
            ->relationship('type_logement', 'nom')
            ->required(),

            Select::make('statut')
            ->options([
                'Disponible' => 'Disponible',
                'Réservé' => 'Réservé',
                'Occupé' => 'Occupé',
                'En maintenance' => 'En maintenance',
            ])
            ->required()
            ->default('Disponible'),

            TextInput::make('etage')
            ->label('Étage')
            ->numeric(),
        ]);
    }
}