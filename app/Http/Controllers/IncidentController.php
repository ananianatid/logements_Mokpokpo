<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'logement_id' => 'required|exists:logements,id',
            'type' => 'required|in:Panne,Dégât,Voisinage',
            'description' => 'required|string|min:10',
        ]);

        $etudiant = Auth::user()?->etudiant;

        if (!$etudiant) {
            return back()->with('error', 'Action non autorisée.');
        }

        Incident::create([
            'logement_id' => $validated['logement_id'],
            'signale_par_id' => $etudiant->id,
            'type' => $validated['type'],
            'description' => $validated['description'],
            'statut' => 'Nouveau',
        ]);

        return back()->with('success', 'Votre signalement a été enregistré. Un technicien sera informé.');
    }
}