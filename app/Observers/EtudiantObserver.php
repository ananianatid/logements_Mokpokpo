<?php

namespace App\Observers;

use App\Models\Etudiant;

class EtudiantObserver
{
    /**
     * Handle the Etudiant "updated" event.
     */
    public function updated(Etudiant $etudiant): void
    {
        // Recalculate priority for all pending housing requests
        $etudiant->demandeLogements()
            ->where(fn($query) => $query->where('statut', '=', 'En attente'))
            ->get()
            ->each(function ($demande) {
            $demande->priorite = $demande->calculatePriorityScore();
            $demande->save();
        });
    }

    /**
     * Handle the Etudiant "deleted" event.
     */
    public function deleted(Etudiant $etudiant): void
    {
    //
    }

    /**
     * Handle the Etudiant "restored" event.
     */
    public function restored(Etudiant $etudiant): void
    {
    //
    }

    /**
     * Handle the Etudiant "force deleted" event.
     */
    public function forceDeleted(Etudiant $etudiant): void
    {
    //
    }
}