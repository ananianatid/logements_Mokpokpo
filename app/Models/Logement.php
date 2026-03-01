<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logement extends Model
{
    protected $fillable = ['numero_chambre', 'batiment_id', 'type_logement_id', 'statut', 'etage'];

    public function batiment()
    {
        return $this->belongsTo(Batiment::class);
    }

    public function type_logement()
    {
        return $this->belongsTo(TypeLogement::class);
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }
}