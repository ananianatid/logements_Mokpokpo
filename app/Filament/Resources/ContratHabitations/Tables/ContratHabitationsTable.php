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
use Filament\Forms\Components\DateTimePicker;
use Filament\Notifications\Notification;
use App\Models\Concierge;
use App\Models\EtatDesLieux;
use App\Models\FacturePaiement;
use Barryvdh\DomPDF\Facade\Pdf;

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

                TextColumn::make('logement.nomenclature')
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

                IconColumn::make('document_signe')
                    ->label('Signé')
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
                        return $record->etatsDesLieux()->exists();
                    })
                    ->form([
                        Select::make('concierge_id')
                            ->label('Concierge responsable')
                            ->options(function (): array {
                                return Concierge::all()->mapWithKeys(function ($concierge) {
                                    return [$concierge->id => "{$concierge->nom} {$concierge->prenom}"];
                                })->toArray();
                            })
                            ->default(function ($record) {
                                // Auto-select the concierge of the building
                                return Concierge::where('batiment_id', $record->logement->batiment_id)->first()?->id;
                            })
                            ->searchable()
                            ->required(),
                        DateTimePicker::make('date_rendez_vous')
                            ->label('Date de rendez-vous pour l\'installation')
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
                            'date_execution' => now(), // prend directement la date du jour
                            'date_rendez_vous' => $data['date_rendez_vous'],
                            'document_signe' => false,
                        ]);

                        // 2. Create Premier versement (3 months)
                        $monthlyPrice = $record->logement->type_logement->prix ?? 0;
                        FacturePaiement::create([
                            'contrat_id' => $record->id,
                            'mois_concerne' => now(),
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
                Action::make('genererPdf')
                    ->label('Générer Contrat (PDF)')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function ($record) {
                        $etudiantNom = $record->etudiant->nom ?? 'Inconnu';
                        $agentNom = $record->administratif->nom ?? 'Inconnu';
                        $date = now()->format('dmY');
                        $filename = "Contrat_{$etudiantNom}_{$agentNom}_{$date}.pdf";

                        $pdf = Pdf::loadView('pdf.contrat', ['contrat' => $record]);
                        
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->stream();
                        }, $filename);
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}