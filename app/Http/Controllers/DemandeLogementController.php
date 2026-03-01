<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DemandeLogement;
use App\Models\Batiment;
use App\Models\TypeLogement;
use Illuminate\Support\Facades\Auth;

class DemandeLogementController extends Controller
{
    public function create()
    {
        $user = Auth::user();

        // 1. Check if profile is complete
        if (!$user->etudiant || !$user->etudiant->profil_complet) {
            return redirect()->route('profile.complete')
                ->with('error', 'Vous devez compléter votre profil avant de faire une demande de logement.');
        }

        // 2. Check if there is already a pending or validated application
        $existingDemande = DemandeLogement::where('etudiant_id', $user->etudiant->id)
            ->whereIn('statut', ['En attente', 'En cours', 'Validée'])
            ->first();

        if ($existingDemande) {
            return redirect()->route('dashboard')
                ->with('error', 'Vous avez déjà une demande en cours ou validée.');
        }

        $batiments = Batiment::all();
        $typeLogements = TypeLogement::all();

        return view('demandes.create', compact('batiments', 'typeLogements'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->etudiant || !$user->etudiant->profil_complet) {
            return redirect()->route('profile.complete')
                ->with('error', 'Profil incomplet.');
        }

        $validated = $request->validate([
            'batiment_id' => ['required', 'exists:batiments,id'],
            'type_logement_id' => ['required', 'exists:type_logements,id'],
        ]);

        DemandeLogement::create([
            'etudiant_id' => $user->etudiant->id,
            'batiment_id' => $validated['batiment_id'],
            'type_logement_id' => $validated['type_logement_id'],
            'date_soumission' => now(),
            'statut' => 'En attente',
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Votre demande de logement a été soumise avec succès !');
    }
}