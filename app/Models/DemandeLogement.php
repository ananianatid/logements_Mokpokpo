<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandeLogement extends Model
{
    protected $fillable = [
        'etudiant_id',
        'batiment_id',
        'type_logement_id',
        'date_soumission',
        'statut',
        'priorite',
        'administratif_id',
        'logement_propose_id',
        'note_traitement',
        'date_traitement'
    ];

    protected $casts = [
        'date_soumission' => 'datetime',
        'date_traitement' => 'datetime',
    ];

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function batiment()
    {
        return $this->belongsTo(Batiment::class);
    }

    public function type_logement()
    {
        return $this->belongsTo(TypeLogement::class);
    }

    public function administratif()
    {
        return $this->belongsTo(Administratif::class);
    }

    public function logement_propose()
    {
        return $this->belongsTo(Logement::class , 'logement_propose_id');
    }

    public function contrat()
    {
        return $this->hasOne(ContratHabitation::class , 'demande_logement_id');
    }

    /**
     * Calcule le score de priorité basé sur les critères :
     * - Moyenne au bac (x5, max 100)
     * - Récence du bac (max 50)
     * - Genre (Féminin +30)
     * - Situation matrimoniale (Célibataire +20)
     * - Handicap (Tout +50, Moteur +50 extra)
     */
    public function calculatePriorityScore(): int
    {
        $etudiant = $this->etudiant;
        if (!$etudiant)
            return 1;

        $points = 0;

        // 1. Moyenne au bac (x5, max 100 points)
        $points += (int)($etudiant->moyenne_bac * 5);

        // 2. Récence du bac (max 50 points)
        if ($etudiant->date_obtention_bac) {
            $yearsSinceBac = $etudiant->date_obtention_bac->diffInYears(now());
            $points += max(0, 10 - (int)$yearsSinceBac) * 5;
        }

        // 3. Sexe (Féminin +30 points)
        if ($etudiant->sexe === 'Feminin') {
            $points += 30;
        }

        // 4. Situation matrimoniale (Célibataire +20 points)
        if ($etudiant->situation_matrimoniale === 'Celibataire') {
            $points += 20;
        }

        // 5. Handicap (Tout +50, Moteur +50 extra, max 100 points)
        $handicaps = $etudiant->handicaps;
        if ($handicaps->isNotEmpty()) {
            $points += 50;
            if ($handicaps->pluck('nom')->contains('Moteur')) {
                $points += 50;
            }
        }

        // Normalisation de 1 à 10 (basée sur un max théorique de 300 points)
        // Score = 1 + (points / 300) * 9, plafonné à 10
        $normalizedScore = 1 + ($points / 30); // 300 / 10 = 30

        return (int)min(10, round($normalizedScore));
    }
}