<?php

namespace App\Services;

use App\Models\ContratHabitation;

class HousingActivationService
{
    /**
     * Check if a contract can be activated and perform activation if so.
     */
    public function tryActivate(ContratHabitation $contrat): bool
    {
        if ($contrat->statut === 'Actif') {
            return true;
        }

        if ($contrat->canBeActivated()) {
            $contrat->update([
                'statut' => 'Actif',
            ]);

            // Update room status
            if ($contrat->logement) {
                $contrat->logement->update([
                    'statut' => 'Occupé',
                ]);
            }

            return true;
        }

        return false;
    }

    /**
     * Get the activation progress percentage.
     */
    public function getActivationProgress(ContratHabitation $contrat): array
    {
        $edlEntree = $contrat->etatsDesLieux()
            ->where(fn($query) => $query->where('type', '=', 'Entrée'))
            ->first();

        $steps = [
            'signatures' => [
                'label' => 'Signatures du contrat',
                'done' => (bool) $contrat->document_signe,
                'date_rendez_vous' => $contrat->date_rendez_vous,
            ],
            'inspection' => [
                'label' => "État des lieux d'entrée",
                'done' => (bool) ($edlEntree && $edlEntree->document_signe),
                'date_rendez_vous' => $edlEntree?->date_rendez_vous,
            ],
            'payments' => [
                'label' => '3 premiers mois payés',
                'count' => $this->getEffectivePaymentCount($contrat),
                'required' => 3,
                'done' => $this->getEffectivePaymentCount($contrat) >= 3,
            ],
        ];

        $doneCount = collect($steps)->filter(function ($step) {
            return $step['done'];
        })->count();
        $totalSteps = count($steps);

        return [
            'steps' => $steps,
            'percentage' => $totalSteps > 0 ? round(($doneCount / $totalSteps) * 100) : 0,
            'is_ready' => $doneCount === $totalSteps,
        ];
    }

    private function getEffectivePaymentCount(ContratHabitation $contrat): int
    {
        $hasDownPayment = $contrat->paiements()
            ->where(function ($query) {
                $query->where('statut', '=', 'Payé')
                    ->where('est_premier_versement', '=', true);
            })
            ->exists();

        if ($hasDownPayment) {
            return 3;
        }

        return (int) $contrat->paiements()
            ->where(function ($query) {
                $query->where('statut', '=', 'Payé');
            })
            ->count();
    }
}