<?php

namespace App\Filament\Resources\TypeLogements\Pages;

use App\Filament\Resources\TypeLogements\TypeLogementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTypeLogements extends ListRecords
{
    protected static string $resource = TypeLogementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
