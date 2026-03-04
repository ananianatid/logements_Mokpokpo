<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistancePrefecture extends Model
{
    protected $table = 'distances_prefectures';

    protected $fillable = ['region', 'prefecture', 'distance'];

    /**
     * Retourne les préfectures groupées par région.
     * Exemple : ['Maritime' => ['Zio (Tsévié)', 'Lacs (Aného)', ...], ...]
     */
    public static function groupedByRegion(): array
    {
        return self::orderBy('region')->orderBy('prefecture')
            ->get()
            ->groupBy('region')
            ->map(fn($items) => $items->pluck('prefecture', 'prefecture')->toArray())
            ->toArray();
    }
}