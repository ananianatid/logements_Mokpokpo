<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TypeLogement;
use App\Models\Logement;
use App\Models\Batiment;

class LogementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create types of housing
        $types = [
            [
                'nom' => 'Studio Individuel',
                'caracteristique' => 'Lit simple, bureau, kitchenette, salle de bain privée. Idéal pour le calme.',
                'prix' => 25000.0,
            ],
            [
                'nom' => 'Chambre Double',
                'caracteristique' => '2 lits simples, 2 bureaux, salle de bain partagée. Convivial et économique.',
                'prix' => 15000.0,
            ],
        ];

        $typeModels = collect();
        foreach ($types as $type) {
            $typeModels->push(TypeLogement::create($type));
        }

        $studioType = $typeModels->where('nom', 'Studio Individuel')->first();
        $doubleType = $typeModels->where('nom', 'Chambre Double')->first();

        // 2. Create actual rooms (logements) for each building
        $batiments = Batiment::all();

        foreach ($batiments as $batiment) {
            $etages = $batiment->nombre_etages ?? 3;
            $chambresParEtage = 8;
            $indexChambre = 0;

            for ($etage = 0; $etage <= $etages; $etage++) {
                for ($numero = 1; $numero <= $chambresParEtage; $numero++) {
                    $numeroChambre = sprintf("%d%02d", $etage, $numero);
                    $indexChambre++;

                    // Determine type based on building requirements
                    $typeLogementId = null;

                    if ($batiment->nom === 'Bâtiment Wole Soyinka') {
                        // 100% Studio Individuel
                        $typeLogementId = $studioType->id;
                    }
                    elseif ($batiment->nom === 'Bâtiment Cheikh Anta Diop') {
                        // 100% Chambre Double
                        $typeLogementId = $doubleType->id;
                    }
                    elseif ($batiment->nom === 'Bâtiment Samir Amin') {
                        // Hybrid 50/50
                        $totalExpected = ($etages + 1) * $chambresParEtage;
                        $typeLogementId = ($indexChambre <= $totalExpected / 2) ? $studioType->id : $doubleType->id;
                    }
                    else {
                        // Default fallback
                        $typeLogementId = $typeModels->random()->id;
                    }

                    Logement::create([
                        'numero_chambre' => "CH-" . $numeroChambre,
                        'batiment_id' => $batiment->id,
                        'type_logement_id' => $typeLogementId,
                        'statut' => 'Disponible',
                        'etage' => $etage,
                    ]);
                }
            }
        }
    }
}