<?php

namespace App\Filament\Resources\RapportsDisciplinaires\Pages;

use App\Filament\Resources\RapportDisciplinaireResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRapportsDisciplinaires extends ListRecords
{
    protected static string $resource = RapportDisciplinaireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}