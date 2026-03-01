<?php

namespace App\Filament\Resources\ContratHabitations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContratHabitationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('etudiant.nom')
                    ->label('Étudiant')
                    ->formatStateUsing(fn ($record) => "{$record->etudiant->nom} {$record->etudiant->prenom}")
                    ->searchable()
                    ->sortable(),

                TextColumn::make('logement.numero_chambre')
                    ->label('Logement')
                    ->sortable(),

                TextColumn::make('date_debut')
                    ->label('Début')
                    ->date('d/m/Y')
                    ->sortable(),
                
                TextColumn::make('date_fin')
                    ->label('Fin')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Brouillon' => 'gray',
                        'En attente de signature' => 'warning',
                        'Signé' => 'info',
                        'Actif' => 'success',
                        'Résilié' => 'danger',
                        'Expiré' => 'danger',
                        default => 'gray',
                    })
                    ->searchable(),

                IconColumn::make('statut_signature_etudiant')
                    ->label('S. Étudiant')
                    ->boolean(),
                
                IconColumn::make('statut_signature_administratif')
                    ->label('S. Agent')
                    ->boolean(),

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