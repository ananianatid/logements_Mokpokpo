<?php

namespace App\Filament\Resources\EtatDesLieuxes\Pages;

use App\Filament\Resources\EtatDesLieuxes\EtatDesLieuxResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEtatDesLieux extends EditRecord
{
    protected static string $resource = EtatDesLieuxResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
