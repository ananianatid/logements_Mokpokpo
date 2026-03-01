<?php

namespace App\Filament\Resources\Handicaps\Pages;

use App\Filament\Resources\Handicaps\HandicapResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHandicap extends EditRecord
{
    protected static string $resource = HandicapResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
