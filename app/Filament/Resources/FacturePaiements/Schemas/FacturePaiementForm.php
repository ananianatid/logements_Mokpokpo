<?php

namespace App\Filament\Resources\FacturePaiements\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;

class FacturePaiementForm
{
    public static function configure(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
            \Filament\Forms\Components\Select::make('contrat_id')
            ->label('Contrat concerné')
            ->relationship('contrat', 'id')
            ->getOptionLabelFromRecordUsing(fn($record) => "Contrat #{$record->id} - {$record->etudiant->nom}")
            ->searchable()
            ->required(),

            \Filament\Forms\Components\DatePicker::make('mois_concerne')
            ->label('Mois concerné')
            ->required(),

            \Filament\Forms\Components\TextInput::make('montant')
            ->label('Montant (FCFA)')
            ->prefix('XOF')
            ->required()
            ->numeric(),

            \Filament\Forms\Components\Toggle::make('est_premier_versement')
            ->label('Premier versement (Caution/Logement)')
            ->required(),

            \Filament\Forms\Components\Select::make('statut')
            ->options([
                'En attente' => 'En attente',
                'Payé' => 'Payé',
                'Rejeté' => 'Rejeté',
            ])
            ->required()
            ->default('En attente'),

            \Filament\Forms\Components\DateTimePicker::make('date_soumission')
            ->label('Date de soumission')
            ->required()
            ->default(now()),

            \Filament\Forms\Components\DateTimePicker::make('date_validation')
            ->label('Date de validation'),

            \Filament\Forms\Components\Select::make('comptable_id')
            ->label('Comptable')
            ->relationship('comptable', 'nom')
            ->getOptionLabelFromRecordUsing(fn($record) => "{$record->nom} {$record->prenom}")
            ->searchable(),

            \Filament\Forms\Components\FileUpload::make('recu_url')
            ->label('Reçu de paiement (Image/PDF)')
            ->disk('public')
            ->directory('paiements'),

            \Filament\Forms\Components\Textarea::make('note_rejet')
            ->label('Motif de rejet (si applicable)')
            ->columnSpanFull(),
        ]);
    }
}