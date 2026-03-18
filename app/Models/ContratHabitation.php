<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContratHabitation extends Model
{
    protected $fillable = [
        'etudiant_id',
        'logement_id',
        'administratif_id',
        'demande_logement_id',
        'date_debut',
        'date_fin',
        'statut',
        'document_signe',
        'fichier_contrat_url',
        'date_rendez_vous'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'document_signe' => 'boolean',
        'date_rendez_vous' => 'datetime',
    ];

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function logement()
    {
        return $this->belongsTo(Logement::class);
    }

    public function administratif()
    {
        return $this->belongsTo(Administratif::class);
    }

    public function demandeLogement()
    {
        return $this->belongsTo(DemandeLogement::class);
    }

    public function etatsDesLieux()
    {
        return $this->hasMany(EtatDesLieux::class , 'contrat_id');
    }

    public function paiements()
    {
        return $this->hasMany(FacturePaiement::class , 'contrat_id');
    }

    public function isActivated(): bool
    {
        return $this->statut === 'Actif';
    }

    public function getPaiementsStatus(): array
    {
        $startDate = $this->date_debut->copy()->startOfMonth();
        $currentDate = now()->startOfMonth();
        $statuses = [];

        $paiements = $this->paiements()->get();
        $hasPremierVersement = $paiements->where('est_premier_versement', true)->where('statut', 'Payé')->isNotEmpty();

        $month = $startDate->copy();
        $monthIndex = 0;

        while ($month <= $currentDate) {
            $monthKey = $month->format('Y-m');
            $label = ucfirst($month->translatedFormat('F Y'));
            
            // Check if covered by premier versement (months 0, 1, 2)
            if ($hasPremierVersement && $monthIndex < 3) {
                $status = [
                    'month' => $label,
                    'statut' => 'Payé',
                    'badge' => 'success',
                    'note' => 'Initial',
                    'montant' => $this->logement->type_logement->prix ?? 0,
                ];
            } else {
                // Look for a specific payment for this month
                $paiement = $paiements->filter(function ($p) use ($month) {
                    return $p->mois_concerne && $p->mois_concerne->format('Y-m') === $month->format('Y-m') && !$p->est_premier_versement;
                })->first();

                if ($paiement) {
                    $status = [
                        'month' => $label,
                        'statut' => $paiement->statut,
                        'badge' => match($paiement->statut) {
                            'Payé' => 'success',
                            'En attente' => 'warning',
                            'Rejeté' => 'danger',
                            default => 'gray'
                        },
                        'montant' => $paiement->montant,
                    ];
                } else {
                    // No payment found for this month
                    $status = [
                        'month' => $label,
                        'statut' => 'En retard',
                        'badge' => 'danger',
                        'montant' => $this->logement->type_logement->prix ?? 0,
                    ];
                }
            }

            $statuses[] = $status;
            $month->addMonth();
            $monthIndex++;
        }

        return array_reverse($statuses); // Latest first
    }

    public function canBeActivated(): bool
    {
        // 1. Signature
        if (!$this->document_signe) {
            return false;
        }

        // 2. État des lieux (Entrée) signé
        $edlEntree = $this->etatsDesLieux()
            ->where('document_signe', '=', true)
            ->exists();

        if (!$edlEntree) {
            return false;
        }

        // 3. Paiements (3 premiers mois)
        $hasDownPayment = $this->paiements()
            ->where(function ($query) {
            $query->where('statut', '=', 'Payé')
                ->where('est_premier_versement', '=', true);
        })
            ->exists();

        if ($hasDownPayment) {
            return true;
        }

        $pagementsPayes = $this->paiements()
            ->where(function ($query) {
            $query->where('statut', '=', 'Payé');
        })
            ->count();

        return $pagementsPayes >= 3;
    }
}