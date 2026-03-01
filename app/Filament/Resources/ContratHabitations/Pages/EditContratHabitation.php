<?php

namespace App\Filament\Resources\ContratHabitations\Pages;

use App\Filament\Resources\ContratHabitations\ContratHabitationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditContratHabitation extends EditRecord
{
    protected static string $resource = ContratHabitationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
