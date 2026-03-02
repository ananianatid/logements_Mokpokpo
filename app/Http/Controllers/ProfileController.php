<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        if ($user->role !== 'Etudiant') {
            return redirect('/dashboard')->withErrors('Accès non autorisé.');
        }

        $etudiant = $user->etudiant;

        if (!$etudiant) {
            $etudiant = \App\Models\Etudiant::create([
                'user_id' => $user->id,
                'nom' => 'inconnu',
                'prenom' => 'inconnu',
                'date_naissance' => now()->subYears(18)->format('Y-m-d'),
                'sexe' => 'Masculin',
                'profil_complet' => false,
            ]);
        }

        $handicaps = \App\Models\Handicap::all();
        $selectedHandicaps = $etudiant->handicaps->pluck('id')->toArray();

        return view('profile.complete', compact('etudiant', 'handicaps', 'selectedHandicaps'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'Etudiant') {
            return redirect('/dashboard')->withErrors('Accès non autorisé.');
        }

        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'date_naissance' => ['required', 'date', 'before:today'],
            'sexe' => ['required', 'in:Masculin,Feminin'],
            'date_obtention_bac' => ['nullable', 'date'],
            'moyenne_bac' => ['nullable', 'numeric', 'min:0', 'max:20'],
            'adresse_actuelle' => ['nullable', 'string', 'max:500'],
            'situation_matrimoniale' => ['nullable', 'in:Celibataire,Marie,Divorce,Veuf'],
            'handicaps' => ['nullable', 'array'],
            'handicaps.*' => ['exists:handicaps,id'],
        ]);

        $etudiant = $user->etudiant;

        if (!$etudiant) {
            return redirect()->back()->withErrors('Profil étudiant introuvable.');
        }

        $etudiant->update([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'date_naissance' => $validated['date_naissance'],
            'sexe' => $validated['sexe'],
            'date_obtention_bac' => $validated['date_obtention_bac'],
            'moyenne_bac' => $validated['moyenne_bac'],
            'adresse_actuelle' => $validated['adresse_actuelle'],
            'situation_matrimoniale' => $validated['situation_matrimoniale'],
            'profil_complet' => true,
        ]);

        $etudiant->handicaps()->sync($request->input('handicaps', []));

        return redirect()->route('dashboard')->with('success', 'Votre profil a été mis à jour avec succès !');
    }
}