<?php

namespace App\Filament\Resources\RapportsDisciplinaires\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class RapportsDisciplinairesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('etudiant.nom')
            ->label('Étudiant')
            ->formatStateUsing(fn($record) => "{$record->etudiant->nom} {$record->etudiant->prenom}")
            ->searchable(['nom', 'prenom']),
            TextColumn::make('type')
            ->badge(),
            TextColumn::make('gravite')
            ->label('Gravité')
            ->badge()
            ->color(fn($state) => match (true) {
            $state >= 8 => 'danger',
            $state >= 5 => 'warning',
            default => 'success',
        }),
            TextColumn::make('statut')
            ->badge(),
            TextColumn::make('rapportePar.name')
            ->label('Rapporté par'),
            TextColumn::make('created_at')
            ->dateTime()
            ->sortable(),
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