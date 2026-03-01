<?php

namespace App\Filament\Resources\DemandeLogements\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use App\Models\Logement;

class DemandeLogementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
            Select::make('etudiant_id')
            ->relationship('etudiant', 'nom')
            ->getOptionLabelFromRecordUsing(fn($record) => "{$record->nom} {$record->prenom}")
            ->searchable()
            ->required(),

            Select::make('batiment_id')
            ->label('Bâtiment souhaité')
            ->relationship('batiment', 'nom')
            ->searchable(),

            Select::make('type_logement_id')
            ->label('Type de chambre souhaité')
            ->relationship('type_logement', 'nom')
            ->searchable(),

            DateTimePicker::make('date_soumission')
            ->required()
            ->default(now()),

            Select::make('statut')
            ->options([
                'En attente' => 'En attente',
                'En cours' => 'En cours',
                'Validée' => 'Validée',
                'Rejetée' => 'Rejetée',
            ])
            ->required()
            ->default('En attente'),

            TextInput::make('priorite')
            ->label('Priorité (0-10)')
            ->required()
            ->numeric()
            ->default(0),

            Select::make('logement_propose_id')
            ->label('Logement attribué')
            ->options(fn(Get $get) =>
        Logement::where('batiment_id', $get('batiment_id'))
        ->where('statut', 'Disponible')
        ->pluck('numero_chambre', 'id')
        )
            ->searchable()
            ->hint('Uniquement les chambres disponibles du bâtiment choisi'),

            Textarea::make('note_traitement')
            ->label('Notes de l\'administration')
            ->columnSpanFull(),

            DateTimePicker::make('date_traitement')
            ->label('Date de traitement'),
        ]);
    }
}