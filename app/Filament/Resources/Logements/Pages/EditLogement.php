<?php

namespace App\Filament\Resources\Logements\Pages;

use App\Filament\Resources\Logements\LogementResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLogement extends EditRecord
{
    protected static string $resource = LogementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
