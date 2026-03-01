<?php

namespace App\Filament\Resources\EtatDesLieuxes\Pages;

use App\Filament\Resources\EtatDesLieuxes\EtatDesLieuxResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEtatDesLieuxes extends ListRecords
{
    protected static string $resource = EtatDesLieuxResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
