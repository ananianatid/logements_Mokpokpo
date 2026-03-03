<?php

namespace App\Filament\Resources\DemandeLogements\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use App\Models\Logement;
use Filament\Schemas\Schema;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class DemandeLogementForm
{
    public static function configure(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
            \Filament\Forms\Components\Select::make('etudiant_id')
            ->relationship('etudiant', 'nom')
            ->getOptionLabelFromRecordUsing(fn($record) => "{$record->nom} {$record->prenom}")
            ->searchable()
            ->required(),

            \Filament\Forms\Components\Select::make('batiment_id')
            ->label('Bâtiment souhaité')
            ->relationship('batiment', 'nom')
            ->searchable(),

            \Filament\Forms\Components\Select::make('type_logement_id')
            ->label('Type de chambre souhaité')
            ->relationship('type_logement', 'nom')
            ->searchable(),

            \Filament\Forms\Components\DateTimePicker::make('date_soumission')
            ->required()
            ->default(now()),

            \Filament\Forms\Components\Select::make('statut')
            ->options([
                'En attente' => 'En attente',
                'En cours' => 'En cours',
                'Validée' => 'Validée',
                'Rejetée' => 'Rejetée',
            ])
            ->required()
            ->default('En attente'),

            \Filament\Forms\Components\TextInput::make('priorite')
            ->label('Priorité (0-10)')
            ->required()
            ->numeric()
            ->default(0),

            \Filament\Forms\Components\Select::make('logement_propose_id')
            ->label('Logement attribué')
            ->options(fn(Get $get) =>
        Logement::with('type_logement')
        ->where('batiment_id', $get('batiment_id'))
        ->where('statut', 'Disponible')
        ->get()
        ->mapWithKeys(fn($logement) => [
        $logement->id => "{$logement->numero_chambre} (" . ($logement->type_logement->nom ?? '?') . ")"
        ])
        )
            ->searchable()
            ->hint('Uniquement les chambres disponibles du bâtiment choisi'),

            \Filament\Forms\Components\Textarea::make('note_traitement')
            ->label('Notes de l\'administration')
            ->columnSpanFull(),

            \Filament\Forms\Components\DateTimePicker::make('date_traitement')
            ->label('Date de traitement'),
        ]);
    }
}