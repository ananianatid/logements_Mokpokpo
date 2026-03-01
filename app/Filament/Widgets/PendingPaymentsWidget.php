<?php

namespace App\Filament\Widgets;

use App\Models\FacturePaiement;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PendingPaymentsWidget extends BaseWidget
{
    protected static ?string $heading = 'Paiements en attente de validation';

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()->isComptable();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
            FacturePaiement::query()->where('statut', 'En attente')->latest()
        )
            ->columns([
            Tables\Columns\TextColumn::make('contrat.etudiant.nom')
            ->label('Étudiant')
            ->formatStateUsing(fn($record) => $record->contrat->etudiant->prenom . ' ' . $record->contrat->etudiant->nom),
            Tables\Columns\TextColumn::make('contrat.logement.numero_chambre')
            ->label('Chambre'),
            Tables\Columns\TextColumn::make('mois_concerne')
            ->label('Mois')
            ->date('M Y'),
            Tables\Columns\TextColumn::make('montant')
            ->label('Montant')
            ->money('XOF'),
            Tables\Columns\IconColumn::make('est_premier_versement')
            ->label('Initial')
            ->boolean(),
        ]);
    }
}