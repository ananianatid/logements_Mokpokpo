<?php

namespace App\Filament\Resources\IncidentTechniques\Pages;

use App\Filament\Resources\IncidentTechniqueResource;
use Filament\Resources\Pages\ListRecords;

class ListIncidentTechniques extends ListRecords
{
    protected static string $resource = IncidentTechniqueResource::class;
}