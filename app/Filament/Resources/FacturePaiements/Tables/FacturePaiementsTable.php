<?php

namespace App\Filament\Resources\FacturePaiements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FacturePaiementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('contrat_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('mois_concerne')
                    ->date()
                    ->sortable(),
                TextColumn::make('montant')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('est_premier_versement')
                    ->boolean(),
                TextColumn::make('statut')
                    ->searchable(),
                TextColumn::make('date_soumission')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('date_validation')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('comptable_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('recu_url')
                    ->searchable(),
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
