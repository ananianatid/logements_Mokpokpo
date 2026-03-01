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
        'statut_signature_etudiant',
        'statut_signature_administratif',
        'date_signature_etudiant',
        'date_signature_administratif',
        'fichier_contrat_url'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'statut_signature_etudiant' => 'boolean',
        'statut_signature_administratif' => 'boolean',
        'date_signature_etudiant' => 'datetime',
        'date_signature_administratif' => 'datetime',
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

    public function canBeActivated(): bool
    {
        // 1. Signatures
        if (!$this->statut_signature_etudiant || !$this->statut_signature_administratif) {
            return false;
        }

        // 2. État des lieux (Entrée) signé
        $edlEntree = $this->etatsDesLieux()->where('type', '=', 'Entrée')->where('signe_etudiant', '=', true)->where('signe_concierge', '=', true)->exists();
        if (!$edlEntree) {
            return false;
        }

        // 3. Paiements (3 premiers mois)
        $pagementsPayes = $this->paiements()->where('statut', '=', 'Payé')->count();
        return $pagementsPayes >= 3;
    }
}