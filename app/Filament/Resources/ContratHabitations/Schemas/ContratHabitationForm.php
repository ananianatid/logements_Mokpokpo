<?php

namespace App\Filament\Resources\ContratHabitations\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;

class ContratHabitationForm
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

            \Filament\Forms\Components\Select::make('logement_id')
            ->label('Logement')
            ->relationship('logement', 'numero_chambre')
            ->searchable()
            ->required(),

            \Filament\Forms\Components\Select::make('administratif_id')
            ->label('Agent Administratif')
            ->relationship('administratif', 'nom')
            ->getOptionLabelFromRecordUsing(fn($record) => "{$record->nom} {$record->prenom}")
            ->searchable()
            ->required(),

            \Filament\Forms\Components\Select::make('demande_logement_id')
            ->label('Demande d\'origine')
            ->relationship('demandeLogement', 'id')
            ->searchable(),

            \Filament\Forms\Components\DatePicker::make('date_debut')
            ->label('Date de début')
            ->required()
            ->default(now()),

            \Filament\Forms\Components\DatePicker::make('date_fin')
            ->label('Date de fin')
            ->required()
            ->default(now()->addYear()),

            \Filament\Forms\Components\Select::make('statut')
            ->options([
                'Brouillon' => 'Brouillon',
                'En attente de signature' => 'En attente de signature',
                'Signé' => 'Signé',
                'Actif' => 'Actif',
                'Résilié' => 'Résilié',
                'Expiré' => 'Expiré',
            ])
            ->required()
            ->default('Brouillon'),

            \Filament\Forms\Components\Toggle::make('statut_signature_etudiant')
            ->label('Signé par l\'étudiant')
            ->required(),

            \Filament\Forms\Components\Toggle::make('statut_signature_administratif')
            ->label('Signé par l\'agent')
            ->required(),

            \Filament\Forms\Components\DateTimePicker::make('date_signature_etudiant')
            ->label('Date signature étudiant'),

            \Filament\Forms\Components\DateTimePicker::make('date_signature_administratif')
            ->label('Date signature agent'),

            \Filament\Forms\Components\FileUpload::make('fichier_contrat_url')
            ->label('Contrat numérisé')
            ->disk('public')
            ->directory('contrats'),
        ]);
    }
}