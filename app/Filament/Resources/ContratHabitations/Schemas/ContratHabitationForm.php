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
            ->getOptionLabelFromRecordUsing(fn($record): string => (string)"{$record->nom} {$record->prenom}")
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
            ->getOptionLabelFromRecordUsing(fn($record): string => (string)"{$record->nom} {$record->prenom}")
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

            \Filament\Forms\Components\DateTimePicker::make('date_rendez_vous')
            ->label('Date de rendez-vous pour signature')
            ->native(false)
            ->displayFormat('d/m/Y H:i'),

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

            \Filament\Forms\Components\Toggle::make('document_signe')
            ->label('Document signé par les deux parties')
            ->default(false),

            \Filament\Forms\Components\FileUpload::make('fichier_contrat_url')
            ->label('Contrat numérisé')
            ->disk('public')
            ->directory('contrats'),
        ]);
    }
}