<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $fillable = ['logement_id', 'signale_par_id', 'type', 'description', 'statut', 'date_signalement', 'technicien_id', 'date_prise_en_charge', 'date_resolution', 'rapport_intervention'];

    protected $casts = [
        'date_signalement' => 'datetime',
        'date_prise_en_charge' => 'datetime',
        'date_resolution' => 'datetime',
    ];

    public function logement()
    {
        return $this->belongsTo(Logement::class);
    }

    public function signale_par()
    {
        return $this->belongsTo(Etudiant::class , 'signale_par_id');
    }

    public function technicien()
    {
        return $this->belongsTo(Technicien::class);
    }
}