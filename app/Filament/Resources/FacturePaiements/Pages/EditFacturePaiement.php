<?php

namespace App\Filament\Resources\FacturePaiements\Pages;

use App\Filament\Resources\FacturePaiements\FacturePaiementResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFacturePaiement extends EditRecord
{
    protected static string $resource = FacturePaiementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
