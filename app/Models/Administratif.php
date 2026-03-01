<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Administratif extends Model
{
    protected $fillable = ['user_id', 'nom', 'prenom', 'matricule', 'bureau', 'telephone'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}