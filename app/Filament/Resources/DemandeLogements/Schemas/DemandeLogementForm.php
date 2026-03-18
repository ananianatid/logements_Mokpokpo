<?php

namespace App\Filament\Resources\DemandeLogements\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use App\Models\Logement;
use Filament\Schemas\Schema;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;

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

            \Filament\Forms\Components\Section::make('Informations sur l\'étudiant')
                ->description('Détails de l\'étudiant ayant fait la demande pour guider l\'attribution.')
                ->schema([
                    \Filament\Forms\Components\Grid::make(3)->schema([
                        \Filament\Forms\Components\Placeholder::make('student_name')
                            ->label('Nom complet')
                            ->content(fn ($record) => $record?->etudiant ? "{$record->etudiant->nom} {$record->etudiant->prenom}" : '-'),
                        
                        \Filament\Forms\Components\Placeholder::make('student_email')
                            ->label('Email personnel')
                            ->content(fn ($record) => $record?->etudiant?->email_personnel ?? '-'),
                            
                        \Filament\Forms\Components\Placeholder::make('student_phone')
                            ->label('Téléphone')
                            ->content(fn ($record) => $record?->etudiant?->telephone ?? '-'),
                            
                        \Filament\Forms\Components\Placeholder::make('student_filiere')
                            ->label('Filière')
                            ->content(fn ($record) => $record?->etudiant?->filiere ?? '-'),
                            
                        \Filament\Forms\Components\Placeholder::make('student_niveau')
                            ->label('Niveau d\'étude')
                            ->content(fn ($record) => $record?->etudiant?->niveau_etude ?? '-'),
                            
                        \Filament\Forms\Components\Placeholder::make('student_moyenne')
                            ->label('Moyenne d\'admission')
                            ->content(fn ($record) => $record?->etudiant?->moyenne_admission ? $record->etudiant->moyenne_admission . ' / 20' : '-'),
                    ])
                ])
                ->hidden(fn ($record) => $record === null)
                ->collapsed(false)
                ->collapsible(),
        ]);
    }
}