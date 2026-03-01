<?php

namespace App\Filament\Widgets;

use App\Models\DemandeLogement;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PendingDemandesWidget extends BaseWidget
{
    protected static ?string $heading = 'Demandes de logement en attente';

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
            DemandeLogement::query()->where('statut', 'En attente')->latest()
        )
            ->columns([
            Tables\Columns\TextColumn::make('etudiant.nom')
            ->label('Étudiant')
            ->formatStateUsing(fn($record) => $record->etudiant->prenom . ' ' . $record->etudiant->nom),
            Tables\Columns\TextColumn::make('batiment.nom')
            ->label('Bâtiment souhaité'),
            Tables\Columns\TextColumn::make('type_logement.nom')
            ->label('Type'),
            Tables\Columns\TextColumn::make('date_soumission')
            ->label('Soumis le')
            ->dateTime()
            ->sortable(),
            Tables\Columns\BadgeColumn::make('priorite')
            ->colors([
                'danger' => 'Haute',
                'warning' => 'Moyenne',
                'success' => 'Basse',
            ]),
        ]);
    }
}