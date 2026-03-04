<?php

namespace App\Filament\Resources\Incidents\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class IncidentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
            // Étudiant
            TextColumn::make('etudiant.nom')
            ->label('Étudiant')
            ->formatStateUsing(fn($state, $record) => $record->etudiant
        ? $record->etudiant->nom . ' ' . $record->etudiant->prenom
        : '—')
            ->searchable(['etudiants.nom', 'etudiants.prenom'])
            ->sortable(),

            // Type
            TextColumn::make('type')
            ->badge()
            ->color(fn($state) => match ($state) {
            'Violence' => 'danger',
            'Dégradation' => 'warning',
            'Voisinage' => 'info',
            'Comportement' => 'gray',
            default => 'secondary',
        })
            ->searchable(),

            // Gravité
            TextColumn::make('gravite')
            ->label('Gravité /10')
            ->badge()
            ->color(fn($state) => match (true) {
            $state >= 8 => 'danger',
            $state >= 5 => 'warning',
            default => 'success',
        })
            ->sortable(),

            // Statut
            TextColumn::make('statut')
            ->badge()
            ->color(fn($state) => match ($state) {
            'Clôturé' => 'success',
            'En cours' => 'warning',
            default => 'gray',
        })
            ->searchable(),

            // Rapporté par
            TextColumn::make('rapportePar.name')
            ->label('Rapporté par')
            ->default('—'),

            // Date
            TextColumn::make('created_at')
            ->label('Date')
            ->dateTime('d/m/Y H:i')
            ->sortable(),
        ])
            ->filters([
            SelectFilter::make('statut')
            ->options([
                'Nouveau' => 'Nouveau',
                'En cours' => 'En cours',
                'Clôturé' => 'Clôturé',
            ]),
            SelectFilter::make('type')
            ->options([
                'Comportement' => 'Comportement',
                'Dégradation' => 'Dégradation',
                'Voisinage' => 'Voisinage',
                'Violence' => 'Violence',
                'Autre' => 'Autre',
            ]),
        ])
            ->recordActions([
            EditAction::make(),
        ])
            ->toolbarActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ])
            ->defaultSort('created_at', 'desc');
    }
}