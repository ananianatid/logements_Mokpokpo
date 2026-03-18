<?php

namespace App\Filament\Resources\EtatDesLieuxes\Pages;

use App\Filament\Resources\EtatDesLieuxes\EtatDesLieuxResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEtatDesLieux extends EditRecord
{
    protected static string $resource = EtatDesLieuxResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('genererPdf')
                ->label('Générer PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->action(function () {
                    $record = $this->record;
                    $etudiantNom = $record->contrat->etudiant->nom ?? 'Inconnu';
                    $conciergeNom = $record->concierge->nom ?? 'Inconnu';
                    $date = now()->format('dmY');
                    $filename = "EtatDesLieux_{$etudiantNom}_{$conciergeNom}_{$date}.pdf";

                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.etat_des_lieux', ['edl' => $record]);
                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->stream();
                    }, $filename);
                }),
            DeleteAction::make(),
        ];
    }
}
