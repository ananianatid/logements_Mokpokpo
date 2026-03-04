<?php

namespace App\Filament\Resources\IncidentTechniques\Pages;

use App\Filament\Resources\IncidentTechniqueResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListIncidentTechniques extends ListRecords
{
    protected static string $resource = IncidentTechniqueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}