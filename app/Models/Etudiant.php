<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    protected $fillable = [
        'user_id', 'nom', 'prenom', 'date_naissance', 'sexe',
        'date_obtention_bac', 'moyenne_bac', 'adresse_actuelle',
        'situation_matrimoniale', 'profil_complet'
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_obtention_bac' => 'date',
        'moyenne_bac' => 'float',
        'profil_complet' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function handicaps()
    {
        return $this->belongsToMany(Handicap::class , 'etudiant_handicaps');
    }

    public function demandeLogements()
    {
        return $this->hasMany(DemandeLogement::class);
    }

    public function contrats()
    {
        return $this->hasMany(ContratHabitation::class);
    }
}