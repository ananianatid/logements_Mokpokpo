<?php

namespace App\Filament\Resources\DemandeLogements\Pages;

use App\Filament\Resources\DemandeLogements\DemandeLogementResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDemandeLogement extends EditRecord
{
    protected static string $resource = DemandeLogementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
