<?php

namespace App\Filament\Resources\FacturePaiements\Pages;

use App\Filament\Resources\FacturePaiements\FacturePaiementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFacturePaiements extends ListRecords
{
    protected static string $resource = FacturePaiementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
