<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RapportDisciplinaire extends Model
{
    protected $table = 'rapports_disciplinaires';

    protected $fillable = [
        'etudiant_id', 'description', 'gravite',
        'rapporte_par_id', 'type', 'statut'
    ];

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class , 'etudiant_id');
    }

    public function rapportePar()
    {
        return $this->belongsTo(User::class , 'rapporte_par_id');
    }
}