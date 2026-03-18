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

    public function getNomenclatureAttribute(): string
    {
        $batimentNom = $this->batiment->nom ?? '??';
        $initials = collect(explode(' ', $batimentNom))
            ->map(fn($word) => mb_substr($word, 0, 1))
            ->join('');
        $initials = strtoupper($initials);

        $etage = $this->etage ?? '0';
        $numero = str_pad($this->numero_chambre, 2, '0', STR_PAD_LEFT);

        return "{$initials}-{$etage}{$numero}";
    }

    public function getNomCompletAttribute(): string
    {
        return "{$this->nomenclature} ({$this->type_logement->nom})";
    }

    public function type_logement()
    {
        return $this->belongsTo(TypeLogement::class);
    }

    public function incidents()
    {
        return $this->hasMany(IncidentTechnique::class);
    }
}