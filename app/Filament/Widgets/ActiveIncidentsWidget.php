<?php

namespace App\Filament\Widgets;

use App\Models\IncidentTechnique;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ActiveIncidentsWidget extends BaseWidget
{
    protected static ?string $heading = 'Incidents Techniques en cours';

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()->isTechnicien();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
            IncidentTechnique::query()->whereIn('statut', ['Nouveau', 'En cours'])->latest()
        )
            ->columns([
            Tables\Columns\TextColumn::make('logement.numero_chambre')
            ->label('Chambre'),
            Tables\Columns\TextColumn::make('type')
            ->label('Type'),
            Tables\Columns\TextColumn::make('description')
            ->limit(50),
            Tables\Columns\TextColumn::make('date_signalement')
            ->label('Signalé le')
            ->dateTime(),
            Tables\Columns\BadgeColumn::make('statut')
            ->colors([
                'danger' => 'Signalé',
                'warning' => 'En cours',
                'success' => 'Résolu',
            ]),
        ]);
    }
}