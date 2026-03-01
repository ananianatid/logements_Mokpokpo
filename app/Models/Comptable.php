<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comptable extends Model
{
    protected $fillable = ['user_id', 'nom', 'prenom', 'matricule', 'telephone', 'signature_url'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}