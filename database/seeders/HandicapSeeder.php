<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Handicap;

class HandicapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $handicaps = [
            ['nom' => 'Visuel', 'description' => 'Troubles de la vue'],
            ['nom' => 'Auditif', 'description' => 'Troubles de l\'audition'],
            ['nom' => 'Moteur', 'description' => 'Troubles de la mobilité'],
            ['nom' => 'Psychique', 'description' => 'Troubles mentaux ou psychiques'],
            ['nom' => 'Intellectuel', 'description' => 'Troubles des fonctions cognitives'],
            ['nom' => 'Autre', 'description' => 'Autre type de handicap'],
        ];

        foreach ($handicaps as $handicap) {
            Handicap::firstOrCreate(['nom' => $handicap['nom']], $handicap);
        }
    }
}