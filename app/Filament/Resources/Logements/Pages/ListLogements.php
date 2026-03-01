<?php

namespace App\Filament\Resources\Logements\Pages;

use App\Filament\Resources\Logements\LogementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLogements extends ListRecords
{
    protected static string $resource = LogementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
