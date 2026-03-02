<?php

namespace App\Filament\Resources\DemandeLogements\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;

class DemandeLogementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('priorite', 'desc')
            ->columns([
                TextColumn::make('etudiant.nom')
                    ->label('Étudiant')
                    ->formatStateUsing(function ($record): string {
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
                    ->color(function (string $state): string {
                        return match ($state) {
                            'En attente' => 'warning',
                            'En cours' => 'info',
                            'Validée' => 'success',
                            'Rejetée' => 'danger',
                            default => 'gray',
                        };
                    })
                    ->searchable(),

                TextColumn::make('priorite')
                    ->label('Priorité')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('logement_propose.nomenclature')
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
                Action::make('approuver')
                    ->label('Approuver & Créer Contrat')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->hidden(fn ($record) => $record->statut !== 'En attente' && $record->statut !== 'En cours')
                    ->requiresConfirmation()
                    ->form([
                        \Filament\Forms\Components\Select::make('logement_propose_id')
                            ->label('Logement à attribuer')
                            ->options(\App\Models\Logement::all()->pluck('nom_complet', 'id')->toArray())
                            ->searchable()
                            ->required()
                            ->default(fn ($record) => $record->logement_propose_id),
                        \Filament\Forms\Components\DatePicker::make('date_debut')
                            ->label('Date de début (Auto: +1 semaine)')
                            ->default(now()->addWeek())
                            ->required(),
                        \Filament\Forms\Components\DatePicker::make('date_fin')
                            ->label('Date de fin (Auto: +3 mois)')
                            ->default(now()->addWeek()->addMonths(3))
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $administratif = auth()->user()->administratif;

                        if (!$administratif) {
                            \Filament\Notifications\Notification::make()
                                ->title('Erreur : Profil administratif non trouvé')
                                ->danger()
                                ->send();
                            return;
                        }

                        // 1. Update the request
                        $record->update([
                            'statut' => 'Validée',
                            'logement_propose_id' => $data['logement_propose_id'],
                        ]);

                        // 2. Create the contract
                        \App\Models\ContratHabitation::create([
                            'etudiant_id' => $record->etudiant_id,
                            'logement_id' => $data['logement_propose_id'],
                            'administratif_id' => $administratif->id,
                            'demande_logement_id' => $record->id,
                            'date_debut' => $data['date_debut'],
                            'date_fin' => $data['date_fin'],
                            'statut' => 'En attente de signature',
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->title('Demande approuvée et contrat généré')
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}