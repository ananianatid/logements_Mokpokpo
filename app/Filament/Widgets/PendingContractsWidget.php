<?php

namespace App\Filament\Widgets;

use App\Models\ContratHabitation;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PendingContractsWidget extends BaseWidget
{
    protected static ?string $heading = 'Contrats en attente de validation';

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ContratHabitation::query()->where('statut', '!=', 'Actif')->where('document_signe', false)->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('etudiant.nom')
                    ->label('Étudiant')
                    ->formatStateUsing(fn($record) => $record->etudiant->prenom . ' ' . $record->etudiant->nom),
                Tables\Columns\TextColumn::make('logement.numero_chambre')
                    ->label('Chambre'),
                Tables\Columns\IconColumn::make('document_signe')
                    ->label('Signé')
                    ->boolean(),
                Tables\Columns\BadgeColumn::make('statut')
                    ->colors([
                        'warning' => 'Brouillon',
                        'success' => 'Actif',
                        'secondary' => 'Terminé',
                    ]),
        ]);
    }
}