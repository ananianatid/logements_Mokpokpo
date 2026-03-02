<?php

namespace App\Http\Controllers;

use App\Models\Batiment;
use App\Models\TypeLogement;
use Illuminate\Http\Request;

class ResidenceController extends Controller
{
    public function index()
    {
        // Fetch all buildings with their rooms and room types
        $batiments = Batiment::with(['logements.type_logement'])->get();

        // Prepare statistics for each building
        $stats = $batiments->map(function ($batiment) {
            $logements = $batiment->logements;

            // Group by housing type
            $typesStats = $logements->groupBy('type_logement_id')->map(function ($group) {
                    $type = $group->first()->type_logement;
                    return [
                    'id' => $type->id,
                    'nom' => $type->nom,
                    'prix' => $type->prix,
                    'total' => $group->count(),
                    'disponible' => $group->where('statut', 'Disponible')->count(),
                    'occupe' => $group->where('statut', 'Occupé')->count(),
                    'maintenance' => $group->where('statut', 'Maintenance')->count(),
                    ];
                }
                )->values();

                return [
                'id' => $batiment->id,
                'nom' => $batiment->nom,
                'adresse' => $batiment->adresse,
                'total_logements' => $logements->count(),
                'disponible_total' => $logements->where('statut', 'Disponible')->count(),
                'occupe_total' => $logements->where('statut', 'Occupé')->count(),
                'types' => $typesStats,
                ];
            });

        return view('residences.index', compact('stats'));
    }
}