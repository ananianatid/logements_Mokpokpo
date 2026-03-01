<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Concierge extends Model
{
    protected $fillable = ['user_id', 'nom', 'prenom', 'matricule', 'telephone', 'batiment_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function batiment()
    {
        return $this->belongsTo(Batiment::class);
    }
}