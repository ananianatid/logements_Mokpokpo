<?php

namespace App\Filament\Resources\DemandeLogements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;

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
                \Filament\Tables\Actions\Action::make('generer_contrat')
                    ->label('Générer Contrat')
                    ->icon('heroicon-o-document-plus')
                    ->color('success')
                    ->hidden(fn ($record) => $record->statut !== 'Validée' || $record->contrat()->exists())
                    ->action(function ($record, array $data) {
                        $contrat = \App\Models\ContratHabitation::create([
                            'etudiant_id' => $record->etudiant_id,
                            'logement_id' => $record->logement_propose_id,
                            'administratif_id' => auth()->id(), // Assuming the agent is logged in
                            'demande_logement_id' => $record->id,
                            'date_debut' => $data['date_debut'],
                            'date_fin' => $data['date_fin'],
                            'statut' => 'En attente de signature',
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->title('Contrat généré avec succès')
                            ->success()
                            ->send();
                    })
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('date_debut')
                            ->label('Date de début')
                            ->default(now())
                            ->required(),
                        \Filament\Forms\Components\DatePicker::make('date_fin')
                            ->label('Date de fin')
                            ->default(now()->addYear())
                            ->required(),
                    ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}