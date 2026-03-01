<?php

namespace App\Filament\Resources\TypeLogements\Pages;

use App\Filament\Resources\TypeLogements\TypeLogementResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTypeLogement extends EditRecord
{
    protected static string $resource = TypeLogementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
