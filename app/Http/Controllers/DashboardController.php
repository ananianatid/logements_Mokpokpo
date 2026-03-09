<?php

namespace App\Http\Controllers;

use App\Models\DemandeLogement;
use App\Models\ContratHabitation;
use App\Services\HousingActivationService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(HousingActivationService $activationService)
    {
        $user = Auth::user();
        $etudiant = $user?->etudiant;

        // Initialize default values
        $demande = null;
        $contrat = null;
        $activationProgress = null;
        $incidents = collect();
        $paymentHistory = null;

        if (!$etudiant) {
            return view('dashboard', compact('user', 'etudiant', 'demande', 'contrat', 'activationProgress', 'incidents', 'paymentHistory'));
        }

        // 1. Get the latest housing application
        $demande = $etudiant->demandeLogements()->latest()->first();

        // 2. Get the current contract with eager loading to prevent N+1 queries
        $contrat = $etudiant->contrats()
            ->with(['logement.batiment', 'logement.type_logement', 'logement.incidents'])
            ->latest()
            ->first();

        if ($contrat) {
            // Try to activate if conditions are met
            $activationService->tryActivate($contrat);
            // Refresh contract status
            $contrat->refresh();
            // Get progress for UI
            $activationProgress = $activationService->getActivationProgress($contrat);
            // Get recent incidents from already loaded relationship
            if ($contrat->logement) {
                $incidents = $contrat->logement->incidents()->latest()->limit(3)->get();
            }
            // Get payment history
            $paymentHistory = $contrat->getPaiementsStatus();
        }


        return view('dashboard', [
            'user' => $user,
            'etudiant' => $etudiant,
            'demande' => $demande,
            'contrat' => $contrat,
            'activationProgress' => $activationProgress,
            'incidents' => $incidents,
            'paymentHistory' => $paymentHistory,
        ]);
    }
}