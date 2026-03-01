<?php

namespace App\Filament\Resources\Handicaps\Pages;

use App\Filament\Resources\Handicaps\HandicapResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHandicaps extends ListRecords
{
    protected static string $resource = HandicapResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
