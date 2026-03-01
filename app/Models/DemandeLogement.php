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
}