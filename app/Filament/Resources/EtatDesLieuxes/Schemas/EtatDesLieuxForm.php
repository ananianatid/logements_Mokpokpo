<?php

namespace App\Filament\Resources\EtatDesLieuxes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;

class EtatDesLieuxForm
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

            \Filament\Forms\Components\Select::make('concierge_id')
            ->label('Concierge')
            ->relationship('concierge', 'nom')
            ->getOptionLabelFromRecordUsing(fn($record) => "{$record->nom} {$record->prenom}")
            ->searchable()
            ->required(),

            \Filament\Forms\Components\Select::make('type')
            ->options([
                'Entrée' => 'Entrée',
                'Sortie' => 'Sortie',
            ])
            ->required(),

            \Filament\Forms\Components\DatePicker::make('date_execution')
            ->label('Date d\'exécution')
            ->required()
            ->default(now()),

            \Filament\Forms\Components\DateTimePicker::make('date_rendez_vous')
            ->label('Date de rendez-vous pour état des lieux')
            ->native(false)
            ->displayFormat('d/m/Y H:i'),

            \Filament\Forms\Components\Textarea::make('remarques_generales')
            ->label('Remarques générales')
            ->columnSpanFull(),

            \Filament\Forms\Components\FileUpload::make('fichier_pdf_url')
            ->label('Document PDF')
            ->disk('public')
            ->directory('etats-des-lieux'),

            \Filament\Forms\Components\Toggle::make('signe_etudiant')
            ->label('Signé par l\'étudiant')
            ->required(),

            \Filament\Forms\Components\Toggle::make('signe_concierge')
            ->label('Signé par le concierge')
            ->required(),

            \Filament\Forms\Components\DateTimePicker::make('date_signature_etudiant')
            ->label('Date signature étudiant'),

            \Filament\Forms\Components\DateTimePicker::make('date_signature_concierge')
            ->label('Date signature concierge'),
        ]);
    }
}