<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistancePrefectureSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('distances_prefectures')->truncate();

        $data = [
            // Maritime
            ['region' => 'Maritime', 'prefecture' => 'Zio (Tsévié)', 'distance' => 35],
            ['region' => 'Maritime', 'prefecture' => 'Lacs (Aného)', 'distance' => 45],
            ['region' => 'Maritime', 'prefecture' => 'Yoto (Tabligbo)', 'distance' => 80],
            ['region' => 'Maritime', 'prefecture' => 'Vo (Vogan)', 'distance' => 55],
            ['region' => 'Maritime', 'prefecture' => 'Avé (Kévé)', 'distance' => 50],

            // Plateaux
            ['region' => 'Plateaux', 'prefecture' => 'Ogou (Atakpamé)', 'distance' => 160],
            ['region' => 'Plateaux', 'prefecture' => 'Kloto (Kpalimé)', 'distance' => 120],
            ['region' => 'Plateaux', 'prefecture' => 'Haho (Notsé)', 'distance' => 95],
            ['region' => 'Plateaux', 'prefecture' => 'Amou (Amlamé)', 'distance' => 190],
            ['region' => 'Plateaux', 'prefecture' => 'Wawa (Badou)', 'distance' => 245],

            // Centrale
            ['region' => 'Centrale', 'prefecture' => 'Tchaoudjo (Sokodé)', 'distance' => 340],
            ['region' => 'Centrale', 'prefecture' => 'Tchamba (Tchamba)', 'distance' => 375],
            ['region' => 'Centrale', 'prefecture' => 'Sotouboua (Sotouboua)', 'distance' => 285],
            ['region' => 'Centrale', 'prefecture' => 'Blitta (Blitta)', 'distance' => 265],

            // Kara
            ['region' => 'Kara', 'prefecture' => 'Kozah (Kara)', 'distance' => 415],
            ['region' => 'Kara', 'prefecture' => 'Assoli (Bafilo)', 'distance' => 385],
            ['region' => 'Kara', 'prefecture' => 'Doufelgou (Niamtougou)', 'distance' => 445],
            ['region' => 'Kara', 'prefecture' => 'Dankpen (Guérin-Kouka)', 'distance' => 470],
            ['region' => 'Kara', 'prefecture' => 'Kéran (Kanté)', 'distance' => 490],

            // Savanes
            ['region' => 'Savanes', 'prefecture' => 'Oti (Mango)', 'distance' => 550],
            ['region' => 'Savanes', 'prefecture' => 'Tône (Dapaong)', 'distance' => 620],
            ['region' => 'Savanes', 'prefecture' => 'Kpendjal (Mandouri)', 'distance' => 650],
            ['region' => 'Savanes', 'prefecture' => 'Cinkassé (Cinkassé)', 'distance' => 660],
        ];

        DB::table('distances_prefectures')->insert($data);
    }
}