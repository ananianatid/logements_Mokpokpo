<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $fillable = [
        'logement_id', 'etudiant_id', 'type', 'description',
        'gravite', 'rapporte_par_id', 'statut', 'date_signalement',
        'technicien_id', 'date_prise_en_charge', 'date_resolution', 'rapport_intervention'
    ];

    protected $casts = [
        'date_signalement' => 'datetime',
        'date_prise_en_charge' => 'datetime',
        'date_resolution' => 'datetime',
    ];

    public function logement()
    {
        return $this->belongsTo(Logement::class);
    }

    /** L'étudiant auteur de l'incident */
    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class , 'etudiant_id');
    }

    /** L'admin ou concierge qui a rédigé le rapport */
    public function rapportePar()
    {
        return $this->belongsTo(User::class , 'rapporte_par_id');
    }

    public function technicien()
    {
        return $this->belongsTo(Technicien::class);
    }
}