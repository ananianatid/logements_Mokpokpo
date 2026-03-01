<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacturePaiement extends Model
{
    protected $fillable = ['contrat_id', 'mois_concerne', 'montant', 'est_premier_versement', 'statut', 'date_soumission', 'date_validation', 'comptable_id', 'recu_url', 'note_rejet'];

    protected $casts = [
        'mois_concerne' => 'date',
        'est_premier_versement' => 'boolean',
        'date_soumission' => 'datetime',
        'date_validation' => 'datetime',
    ];

    public function contrat()
    {
        return $this->belongsTo(ContratHabitation::class , 'contrat_id');
    }

    public function comptable()
    {
        return $this->belongsTo(Comptable::class);
    }
}