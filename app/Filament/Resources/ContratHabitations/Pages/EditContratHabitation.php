<?php

namespace App\Filament\Resources\ContratHabitations\Pages;

use App\Filament\Resources\ContratHabitations\ContratHabitationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditContratHabitation extends EditRecord
{
    protected static string $resource = ContratHabitationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('genererPdf')
                ->label('Générer Contrat (PDF)')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->action(function () {
                    $record = $this->record;
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.contrat', ['contrat' => $record]);
                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->stream();
                    }, "Contrat_{$record->id}_{$record->etudiant->nom}.pdf");
                }),
            DeleteAction::make(),
        ];
    }
}
