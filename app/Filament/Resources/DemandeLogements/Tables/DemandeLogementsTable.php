<?php

namespace App\Filament\Resources\DemandeLogements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DemandeLogementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('etudiant.nom')
                    ->label('Étudiant')
                    ->formatStateUsing(function ($record) {
                        return $record->etudiant ? "{$record->etudiant->nom} {$record->etudiant->prenom}" : 'Inconnu';
                    })
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('batiment.nom')
                    ->label('Bâtiment (Préférence)')
                    ->sortable(),
                
                TextColumn::make('type_logement.nom')
                    ->label('Type (Préférence)')
                    ->sortable(),

                TextColumn::make('statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'En attente' => 'warning',
                        'En cours' => 'info',
                        'Validée' => 'success',
                        'Rejetée' => 'danger',
                        default => 'gray',
                    })
                    ->searchable(),

                TextColumn::make('priorite')
                    ->label('Priorité')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('logement_propose.numero_chambre')
                    ->label('Logement Attribué')
                    ->placeholder('Non assigné')
                    ->sortable(),

                TextColumn::make('date_soumission')
                    ->label('Soumis le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}