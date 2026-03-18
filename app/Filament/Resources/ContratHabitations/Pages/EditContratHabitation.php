<?php

namespace App\Filament\Resources\ContratHabitations\Pages;

use App\Filament\Resources\ContratHabitations\ContratHabitationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Notifications\Notification;
use App\Models\Concierge;
use App\Models\EtatDesLieux;
use App\Models\FacturePaiement;

class EditContratHabitation extends EditRecord
{
    protected static string $resource = ContratHabitationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('initierInstallation')
                ->label('Initier Installation')
                ->icon('heroicon-o-home-modern')
                ->color('info')
                ->hidden(function (): bool {
                    return $this->record->etatsDesLieux()->where('type', '=', 'Entrée')->exists();
                })
                ->form([
                    Select::make('concierge_id')
                        ->label('Concierge responsable')
                        ->options(function (): array {
                            return Concierge::all()->mapWithKeys(function ($concierge) {
                                return [$concierge->id => "{$concierge->nom} {$concierge->prenom}"];
                            })->toArray();
                        })
                        ->default(function () {
                            // Auto-select the concierge of the building
                            return Concierge::where('batiment_id', $this->record->logement->batiment_id)->first()?->id;
                        })
                        ->searchable()
                        ->required(),
                    DateTimePicker::make('date_rendez_vous')
                        ->label('Date de rendez-vous pour l\'installation')
                        ->default(function () {
                            return $this->record->date_debut;
                        })
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $record = $this->record;
                    
                    // 1. Create Etat des lieux (Entrée)
                    EtatDesLieux::create([
                        'contrat_id' => $record->id,
                        'concierge_id' => $data['concierge_id'],
                        'type' => 'Entrée',
                        'date_execution' => now(), // prend directement la date du jour
                        'date_rendez_vous' => $data['date_rendez_vous'],
                        'signe_etudiant' => false,
                        'signe_concierge' => false,
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
            \Filament\Actions\Action::make('genererPdf')
                ->label('Générer Contrat (PDF)')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->action(function () {
                    $record = $this->record;
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.contrat', ['contrat' => $record]);
                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->stream();
                    }, "Contrat_{$record->id}_{$record->etudiant->nom}.pdf");
                }),
            DeleteAction::make(),
        ];
    }
}

