<?php

namespace App\Filament\Resources\Etudiants\Pages;

use App\Filament\Resources\Etudiants\EtudiantResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEtudiant extends CreateRecord
{
    protected static string $resource = EtudiantResource::class;
}
