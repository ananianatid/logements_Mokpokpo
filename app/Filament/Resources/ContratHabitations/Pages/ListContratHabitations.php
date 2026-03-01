<?php

namespace App\Filament\Resources\ContratHabitations\Pages;

use App\Filament\Resources\ContratHabitations\ContratHabitationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListContratHabitations extends ListRecords
{
    protected static string $resource = ContratHabitationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
