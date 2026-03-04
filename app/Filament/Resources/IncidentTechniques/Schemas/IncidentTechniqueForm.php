<?php

namespace App\Filament\Resources\IncidentTechniques\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class IncidentTechniqueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
            Select::make('logement_id')
            ->relationship('logement', 'numero_chambre')
            ->required()
            ->searchable(),
            Select::make('etudiant_id')
            ->relationship('etudiant', 'nom')
            ->getOptionLabelFromRecordUsing(fn($record): string => (string)"{$record->nom} {$record->prenom}")
            ->required()
            ->searchable(),
            Select::make('type')
            ->options([
                'Panne' => 'Panne',
                'Dégât' => 'Dégât',
                'Voisinage' => 'Voisinage',
                'Autre' => 'Autre',
            ])
            ->required(),
            DateTimePicker::make('date_signalement')
            ->default(now())
            ->required(),
            Textarea::make('description')
            ->required()
            ->columnSpanFull(),
            Select::make('statut')
            ->options([
                'Nouveau' => 'Nouveau',
                'En cours' => 'En cours',
                'Résolu' => 'Résolu',
            ])
            ->default('Nouveau')
            ->required(),
            Select::make('technicien_id')
            ->relationship('technicien', 'prenom')
            ->getOptionLabelFromRecordUsing(fn($record): string => (string)"{$record->nom} {$record->prenom}")
            ->searchable()
            ->nullable(),
            DateTimePicker::make('date_prise_en_charge'),
            DateTimePicker::make('date_resolution'),
            Textarea::make('rapport_intervention')
            ->columnSpanFull(),
        ]);
    }
}