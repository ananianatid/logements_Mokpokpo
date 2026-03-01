<?php

namespace App\Services;

use App\Models\ContratHabitation;
use App\Models\Logement;
use App\Models\FacturePaiement;

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
            $contrat->logement->update([
                'statut' => 'Occupé',
            ]);

            return true;
        }

        return false;
    }

    /**
     * Get the activation progress percentage.
     */
    public function getActivationProgress(ContratHabitation $contrat): array
    {
        $steps = [
            'signatures' => [
                'label' => 'Signatures du contrat',
                'done' => $contrat->statut_signature_etudiant && $contrat->statut_signature_administratif,
            ],
                'inspection' => [
                    'label' => 'État des lieux d\'entrée',
                    'done' => $contrat->etatsDesLieux()->where('type', '=', 'Entrée')->where('signe_etudiant', '=', true)->where('signe_concierge', '=', true)->exists(),
                ],
                'payments' => [
                    'label' => '3 premiers mois payés',
                    'count' => $contrat->paiements()->where('statut', '=', 'Payé')->count(),
                    'required' => 3,
                    'done' => $contrat->paiements()->where('statut', '=', 'Payé')->count() >= 3,
                ],
        ];

        $doneCount = collect($steps)->filter(fn($step) => $step['done'])->count();
        $totalSteps = count($steps);

        return [
            'steps' => $steps,
            'percentage' => round(($doneCount / $totalSteps) * 100),
            'is_ready' => $doneCount === $totalSteps,
        ];
    }
}