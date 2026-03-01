<?php

namespace App\Filament\Resources\ContratHabitations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use App\Models\Concierge;
use App\Models\EtatDesLieux;
use App\Models\FacturePaiement;

class ContratHabitationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('etudiant.nom')
                    ->label('État de l\'étudiant')
                    ->formatStateUsing(function ($record): string {
                        return "{$record->etudiant->nom} {$record->etudiant->prenom}";
                    })
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
                    ->color(function (string $state): string {
                        return match ($state) {
                            'Brouillon' => 'gray',
                            'En attente de signature' => 'warning',
                            'Signé' => 'info',
                            'Actif' => 'success',
                            'Résilié' => 'danger',
                            'Expiré' => 'danger',
                            default => 'gray',
                        };
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
                Action::make('initierInstallation')
                    ->label('Initier Installation')
                    ->icon('heroicon-o-home-modern')
                    ->color('info')
                    ->hidden(function ($record): bool {
                        return $record->etatsDesLieux()->where('type', '=', 'Entrée')->exists();
                    })
                    ->form([
                        Select::make('concierge_id')
                            ->label('Concierge responsable')
                            ->options(function (): array {
                                return Concierge::all()->mapWithKeys(function ($concierge) {
                                    return [$concierge->id => "{$concierge->nom} {$concierge->prenom}"];
                                })->toArray();
                            })
                            ->searchable()
                            ->required(),
                        DatePicker::make('date_installation')
                            ->label('Date d\'installation prévue')
                            ->default(function ($record) {
                                return $record->date_debut;
                            })
                            ->required(),
                    ])
                    ->action(function ($record, array $data): void {
                        // 1. Create Etat des lieux (Entrée)
                        EtatDesLieux::create([
                            'contrat_id' => $record->id,
                            'concierge_id' => $data['concierge_id'],
                            'type' => 'Entrée',
                            'date_execution' => $data['date_installation'],
                            'signe_etudiant' => false,
                            'signe_concierge' => false,
                        ]);

                        // 2. Create Premier versement (3 months)
                        $monthlyPrice = $record->logement->type_logement->prix ?? 0;
                        FacturePaiement::create([
                            'contrat_id' => $record->id,
                            'mois_concerne' => $data['date_installation'],
                            'montant' => $monthlyPrice * 3, // Initial payment for 3 months
                            'est_premier_versement' => true,
                            'statut' => 'En attente',
                            'date_soumission' => now(),
                        ]);

                        Notification::make()
                            ->title('Installation initiée')
                            ->body('L\'état des lieux d\'entrée et la facture initiale ont été générés.')
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