<?php

namespace App\Filament\Resources\Incidents\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class IncidentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('logement_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('signale_par_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('type')
                    ->searchable(),
                TextColumn::make('statut')
                    ->searchable(),
                TextColumn::make('date_signalement')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('technicien_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('date_prise_en_charge')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('date_resolution')
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
