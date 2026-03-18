<?php

namespace App\Filament\Resources\DemandeLogements\Pages;

use App\Filament\Resources\DemandeLogements\DemandeLogementResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDemandeLogement extends EditRecord
{
    protected static string $resource = DemandeLogementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('genererContrat')
                ->label('Générer le contrat')
                ->icon('heroicon-o-document-plus')
                ->color('success')
                ->visible(fn () => $this->record->statut === 'Validée' && $this->record->logement_propose_id !== null && !$this->record->contrat)
                ->requiresConfirmation()
                ->action(function () {
                    $demande = $this->record;
                    $administratif = auth()->user()->administratif;

                    if (!$administratif) {
                        \Filament\Notifications\Notification::make()
                            ->title('Erreur : Profil administratif non trouvé')
                            ->danger()
                            ->send();
                        return;
                    }
                    
                    $contrat = \App\Models\ContratHabitation::create([
                        'etudiant_id' => $demande->etudiant_id,
                        'logement_id' => $demande->logement_propose_id,
                        'administratif_id' => $administratif->id,
                        'demande_logement_id' => $demande->id,
                        'date_debut' => now(),
                        'date_fin' => now()->addYear(),
                        'statut' => 'Brouillon',
                        'document_signe' => false,
                    ]);

                    // Update room status to Reserved
                    if ($demande->logement_propose) {
                        $demande->logement_propose->update(['statut' => 'Réservé']);
                    }

                    \Filament\Notifications\Notification::make()
                        ->title('Contrat généré avec succès')
                        ->success()
                        ->send();
                        
                    return redirect(route('filament.admin.resources.contrat-habitations.edit', ['record' => $contrat->id]));
                }),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['date_traitement'] = now();
        return $data;
    }
}
