<?php

namespace App\Filament\Widgets;

use App\Models\EtatDesLieux;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PendingInspectionsWidget extends BaseWidget
{
    protected static ?string $heading = 'États des lieux à effectuer/valider';

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()->isConcierge();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
            EtatDesLieux::query()
            ->where(function ($query) {
            $query->where('signe_concierge', false)
                ->orWhere('signe_etudiant', false);
        })
            ->latest()
        )
            ->columns([
            Tables\Columns\TextColumn::make('contrat.etudiant.nom')
            ->label('Étudiant')
            ->formatStateUsing(fn($record) => $record->contrat->etudiant->prenom . ' ' . $record->contrat->etudiant->nom),
            Tables\Columns\TextColumn::make('contrat.logement.numero_chambre')
            ->label('Chambre'),
            Tables\Columns\BadgeColumn::make('type')
            ->colors([
                'primary' => 'Entrée',
                'warning' => 'Sortie',
            ]),
            Tables\Columns\IconColumn::make('signe_concierge')
            ->label('Signé Concierge')
            ->boolean(),
            Tables\Columns\IconColumn::make('signe_etudiant')
            ->label('Signé Étudiant')
            ->boolean(),
        ]);
    }
}