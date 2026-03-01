<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Handicap extends Model
{
    protected $fillable = ['nom', 'description'];

    public function etudiants()
    {
        return $this->belongsToMany(Etudiant::class , 'etudiant_handicaps');
    }
}