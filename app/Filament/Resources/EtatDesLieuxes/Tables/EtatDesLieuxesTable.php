<?php

namespace App\Filament\Resources\EtatDesLieuxes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EtatDesLieuxesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('contrat_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('concierge_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('type')
                    ->searchable(),
                TextColumn::make('date_execution')
                    ->date()
                    ->sortable(),
                TextColumn::make('fichier_pdf_url')
                    ->searchable(),
                IconColumn::make('signe_etudiant')
                    ->boolean(),
                IconColumn::make('signe_concierge')
                    ->boolean(),
                TextColumn::make('date_signature_etudiant')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('date_signature_concierge')
                    ->dateTime()
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
