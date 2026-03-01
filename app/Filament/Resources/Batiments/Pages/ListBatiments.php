<?php

namespace App\Filament\Resources\Batiments\Pages;

use App\Filament\Resources\Batiments\BatimentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBatiments extends ListRecords
{
    protected static string $resource = BatimentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
