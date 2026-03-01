<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Technicien extends Model
{
    protected $fillable = ['user_id', 'nom', 'prenom', 'matricule', 'telephone', 'specialite', 'disponible'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}