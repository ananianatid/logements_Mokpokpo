<?php

namespace App\Filament\Widgets;

use App\Models\Logement;
use App\Models\Batiment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class HousingOverview extends BaseWidget
{
    protected static ?int $sort = -2;

    public static function canView(): bool
    {
        return auth()->user()->isAdmin() || auth()->user()->isConcierge();
    }

    protected function getStats(): array
    {
        $total = Logement::count();
        $dispo = Logement::where('statut', 'Disponible')->count();
        $occupe = $total - $dispo;
        $tauxOcc = $total > 0 ? round(($occupe / $total) * 100, 1) : 0;

        return [
            Stat::make('Total Logements', $total)
            ->description('Capacité totale du campus')
            ->descriptionIcon('heroicon-m-home')
            ->color('info'),
            Stat::make('Chambres Disponibles', $dispo)
            ->description($dispo > 0 ? 'Prêtes à être attribuées' : 'Complet')
            ->descriptionIcon('heroicon-m-check-circle')
            ->color($dispo > 10 ? 'success' : 'warning'),
            Stat::make('Taux d\'Occupation', $tauxOcc . '%')
            ->description('Remplissage actuel')
            ->descriptionIcon('heroicon-m-chart-bar')
            ->chart([7, 3, 4, 5, 6, 3, $tauxOcc / 10])
            ->color($tauxOcc > 90 ? 'danger' : 'primary'),
        ];
    }
}