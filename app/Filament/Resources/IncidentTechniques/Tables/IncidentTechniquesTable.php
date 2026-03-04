<?php

namespace App\Filament\Resources\IncidentTechniques\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class IncidentTechniquesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('logement.numero_chambre')
            ->label('Logement')
            ->sortable(),
            TextColumn::make('etudiant.nom')
            ->label('Étudiant')
            ->formatStateUsing(fn($record) => "{$record->etudiant->nom} {$record->etudiant->prenom}")
            ->searchable(['nom', 'prenom']),
            TextColumn::make('type')
            ->badge()
            ->sortable(),
            TextColumn::make('statut')
            ->badge()
            ->color(fn(string $state): string => match ($state) {
            'Nouveau' => 'gray',
            'En cours' => 'warning',
            'Résolu' => 'success',
        })
            ->sortable(),
            TextColumn::make('date_signalement')
            ->dateTime()
            ->sortable(),
            TextColumn::make('technicien.prenom')
            ->label('Technicien')
            ->placeholder('Non assigné'),
        ])
            ->filters([
            //
        ])
            ->actions([
            EditAction::make(),
        ])
            ->bulkActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ]);
    }
}