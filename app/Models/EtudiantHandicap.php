<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EtudiantHandicap extends Model
{
    protected $table = 'etudiant_handicaps';
    protected $fillable = ['etudiant_id', 'handicap_id'];
}