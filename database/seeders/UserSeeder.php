<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Etudiant;
use App\Models\Administratif;
use App\Models\Comptable;
use App\Models\Concierge;
use App\Models\Technicien;
use App\Models\Batiment;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('fr_FR');
        $password = Hash::make('password'); // Default password for all

        // 1. Create Batiments with names of great African academics
        $batimentsData = [
            ['nom' => 'Bâtiment Wole Soyinka', 'adresse' => 'Zone Littéraire, Campus Mokpokpo', 'nombre_etages' => 4],
            ['nom' => 'Bâtiment Cheikh Anta Diop', 'adresse' => 'Zone Scientifique, Campus Mokpokpo', 'nombre_etages' => 5],
            ['nom' => 'Bâtiment Samir Amin', 'adresse' => 'Zone Économique, Campus Mokpokpo', 'nombre_etages' => 3],
        ];

        $batiments = collect();
        foreach ($batimentsData as $data) {
            $batiments->push(Batiment::create($data));
        }

        // Pick a random batiment for the Concierge
        $batimentForConcierge = $batiments->random();

        // Helper to formatting email: removed accents, spaces etc.
        $formatEmail = function ($nom, $prenom) {
            $nom = Str::slug($nom, '');
            $prenom = Str::slug($prenom, '');
            return "{$nom}{$prenom}@mokpokpo.tg";
        };

        // 2. 20 Etudiants
        for ($i = 0; $i < 20; $i++) {
            $nom = $faker->lastName;
            $prenom = $faker->firstName;
            // Generate a unique email
            $email = $formatEmail($nom, $prenom);
            // In case of duplicate emails
            $counter = 1;
            while (User::where('email', $email)->exists()) {
                $email = $formatEmail($nom, $prenom . $counter);
                $counter++;
            }

            $user = User::create([
                'email' => $email,
                'password' => $password,
                'role' => 'Etudiant',
                'is_active' => true,
            ]);

            Etudiant::create([
                'user_id' => $user->id,
                'nom' => $nom,
                'prenom' => $prenom,
                'date_naissance' => $faker->date(),
                'sexe' => $faker->randomElement(['Masculin', 'Feminin']),
                'profil_complet' => true,
            ]);
        }

        // 3. 1 Administratif
        $nomAdmin = $faker->lastName;
        $prenomAdmin = $faker->firstName;
        $emailAdmin = $formatEmail($nomAdmin, $prenomAdmin);

        $userAdmin = User::create([
            'email' => $emailAdmin,
            'password' => $password,
            'role' => 'Administratif',
            'is_active' => true,
        ]);
        Administratif::create([
            'user_id' => $userAdmin->id,
            'nom' => $nomAdmin,
            'prenom' => $prenomAdmin,
            'matricule' => 'ADM-' . $faker->unique()->numerify('####'),
            'bureau' => 'Bureau ' . $faker->numberBetween(1, 10),
            'telephone' => $faker->phoneNumber,
        ]);

        // 4. 1 Comptable
        $nomComp = $faker->lastName;
        $prenomComp = $faker->firstName;
        $userComp = User::create([
            'email' => $formatEmail($nomComp, $prenomComp),
            'password' => $password,
            'role' => 'Comptable',
            'is_active' => true,
        ]);
        Comptable::create([
            'user_id' => $userComp->id,
            'nom' => $nomComp,
            'prenom' => $prenomComp,
            'matricule' => 'CMP-' . $faker->unique()->numerify('####'),
            'telephone' => $faker->phoneNumber,
        ]);

        // 5. 3 Concierges (1 per Batiment)
        $conciergeCounter = 1;
        foreach ($batiments as $batiment) {
            $nomConc = $faker->lastName;
            $prenomConc = $faker->firstName;

            $emailConc = $formatEmail($nomConc, $prenomConc);
            while (User::where('email', $emailConc)->exists()) {
                $emailConc = $formatEmail($nomConc, $prenomConc . $conciergeCounter);
                $conciergeCounter++;
            }

            $userConc = User::create([
                'email' => $emailConc,
                'password' => $password,
                'role' => 'Concierge',
                'is_active' => true,
            ]);
            Concierge::create([
                'user_id' => $userConc->id,
                'nom' => $nomConc,
                'prenom' => $prenomConc,
                'matricule' => 'CNC-' . $faker->unique()->numerify('####'),
                'telephone' => $faker->phoneNumber,
                'batiment_id' => $batiment->id,
            ]);
        }

        // 6. 1 Technicien
        $nomTech = $faker->lastName;
        $prenomTech = $faker->firstName;
        $userTech = User::create([
            'email' => $formatEmail($nomTech, $prenomTech),
            'password' => $password,
            'role' => 'Technicien',
            'is_active' => true,
        ]);
        Technicien::create([
            'user_id' => $userTech->id,
            'nom' => $nomTech,
            'prenom' => $prenomTech,
            'matricule' => 'TEC-' . $faker->unique()->numerify('####'),
            'telephone' => $faker->phoneNumber,
            'specialite' => $faker->randomElement(['Electricité', 'Plomberie', 'Menuiserie', 'Climatisation', 'Informatique', 'Autre']),
            'disponible' => true,
        ]);
    }
}