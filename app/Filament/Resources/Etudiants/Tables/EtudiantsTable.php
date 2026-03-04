<?php

namespace App\Filament\Resources\Etudiants\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EtudiantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nom')
                    ->searchable(),
                TextColumn::make('prenom')
                    ->searchable(),
                TextColumn::make('date_naissance')
                    ->date()
                    ->sortable(),
                TextColumn::make('sexe')
                    ->searchable(),
                TextColumn::make('annee_obtention_bac')
                    ->sortable(),
                TextColumn::make('moyenne_bac')
                    ->sortable()
                    ->label('Moyenne Bac'),
                TextColumn::make('prefecture_origine')
                    ->label('Préfecture d\'origine')
                    ->searchable(),
                TextColumn::make('situation_matrimoniale')
                    ->searchable(),
                IconColumn::make('profil_complet')
                    ->boolean(),
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