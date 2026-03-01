<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContratHabitation extends Model
{
    protected $fillable = ['etudiant_id', 'logement_id', 'administratif_id', 'date_debut', 'date_fin', 'statut', 'statut_signature_etudiant', 'statut_signature_administratif', 'date_signature_etudiant', 'date_signature_administratif', 'fichier_contrat_url'];

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
}