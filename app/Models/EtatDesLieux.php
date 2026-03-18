<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EtatDesLieux extends Model
{
    protected $table = 'etat_des_lieuxes';
    protected $fillable = ['contrat_id', 'concierge_id', 'type', 'date_execution', 'date_rendez_vous', 'remarques_generales', 'fichier_pdf_url', 'document_signe'];

    protected $casts = [
        'date_execution' => 'date',
        'document_signe' => 'boolean',
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