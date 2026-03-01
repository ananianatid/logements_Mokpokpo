<?php

namespace App\Filament\Resources\Batiments\Pages;

use App\Filament\Resources\Batiments\BatimentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBatiment extends EditRecord
{
    protected static string $resource = BatimentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
