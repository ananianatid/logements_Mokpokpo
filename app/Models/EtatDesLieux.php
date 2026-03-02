<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EtatDesLieux extends Model
{
    protected $table = 'etat_des_lieuxes';
    protected $fillable = ['contrat_id', 'concierge_id', 'type', 'date_execution', 'date_rendez_vous', 'remarques_generales', 'fichier_pdf_url', 'signe_etudiant', 'signe_concierge', 'date_signature_etudiant', 'date_signature_concierge'];

    protected $casts = [
        'date_execution' => 'date',
        'signe_etudiant' => 'boolean',
        'signe_concierge' => 'boolean',
        'date_signature_etudiant' => 'datetime',
        'date_signature_concierge' => 'datetime',
        'date_rendez_vous' => 'datetime',
    ];

    public function contrat()
    {
        return $this->belongsTo(ContratHabitation::class , 'contrat_id');
    }

    public function concierge()
    {
        return $this->belongsTo(Concierge::class);
    }
}