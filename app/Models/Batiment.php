<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batiment extends Model
{
    protected $fillable = ['nom', 'adresse', 'nombre_etages'];

    public function logements()
    {
        return $this->hasMany(Logement::class);
    }
}