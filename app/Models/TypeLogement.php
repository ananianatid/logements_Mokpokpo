<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeLogement extends Model
{
    protected $table = 'type_logements';
    protected $fillable = ['nom', 'caracteristique', 'prix'];

    public function logements()
    {
        return $this->hasMany(Logement::class);
    }
}