<?php

namespace App\Filament\Resources\Etudiants\Pages;

use App\Filament\Resources\Etudiants\EtudiantResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEtudiant extends EditRecord
{
    protected static string $resource = EtudiantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
