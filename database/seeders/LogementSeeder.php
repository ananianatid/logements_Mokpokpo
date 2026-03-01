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
            [
                'nom' => 'Appartement Partagé',
                'caracteristique' => '3 à 4 chambres individuelles avec cuisine et salon communs.',
                'prix' => 35000.0,
            ],
        ];

        $typeModels = collect();
        foreach ($types as $type) {
            $typeModels->push(TypeLogement::create($type));
        }

        // 2. Create actual rooms (logements) for each building
        $batiments = Batiment::all();

        foreach ($batiments as $batiment) {
            $etages = $batiment->nombre_etages ?? 3;
            $chambresParEtage = 8;

            for ($etage = 0; $etage <= $etages; $etage++) {
                for ($numero = 1; $numero <= $chambresParEtage; $numero++) {
                    $numeroChambre = sprintf("%d%02d", $etage, $numero);

                    Logement::create([
                        'numero_chambre' => "CH-" . $numeroChambre,
                        'batiment_id' => $batiment->id,
                        'type_logement_id' => $typeModels->random()->id,
                        'statut' => 'Disponible',
                        'etage' => $etage,
                    ]);
                }
            }
        }
    }
}