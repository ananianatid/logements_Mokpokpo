<?php

namespace App\Filament\Resources\Logements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LogementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomenclature')
                    ->label('Chambre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('batiment.nom')
                    ->label('Bâtiment')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type_logement.nom')
                    ->label('Type')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Disponible' => 'success',
                        'Réservé' => 'warning',
                        'Occupé' => 'info',
                        'En maintenance' => 'danger',
                        default => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('etage')
                    ->label('Étage')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
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