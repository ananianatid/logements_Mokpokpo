<?php

namespace App\Filament\Resources\DemandeLogements\Pages;

use App\Filament\Resources\DemandeLogements\DemandeLogementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDemandeLogements extends ListRecords
{
    protected static string $resource = DemandeLogementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
