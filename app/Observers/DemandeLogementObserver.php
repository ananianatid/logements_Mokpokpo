<?php

namespace App\Observers;

use App\Models\DemandeLogement;

class DemandeLogementObserver
{
    /**
     * Handle the DemandeLogement "creating" event.
     */
    public function creating(DemandeLogement $demandeLogement): void
    {
        $demandeLogement->priorite = $demandeLogement->calculatePriorityScore();
    }

    /**
     * Handle the DemandeLogement "updated" event.
     */
    public function updated(DemandeLogement $demandeLogement): void
    {
    //
    }

    /**
     * Handle the DemandeLogement "deleted" event.
     */
    public function deleted(DemandeLogement $demandeLogement): void
    {
    //
    }

    /**
     * Handle the DemandeLogement "restored" event.
     */
    public function restored(DemandeLogement $demandeLogement): void
    {
    //
    }

    /**
     * Handle the DemandeLogement "force deleted" event.
     */
    public function forceDeleted(DemandeLogement $demandeLogement): void
    {
    //
    }
}