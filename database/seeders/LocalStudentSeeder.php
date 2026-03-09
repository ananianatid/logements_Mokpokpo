<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Etudiant;
use Illuminate\Support\Facades\Hash;

class LocalStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'etudiant@mokpokpo.tg';
        $password = 'password';

        // Création de l'utilisateur
        $user = User::firstOrCreate(
        ['email' => $email],
        [
            'password' => Hash::make($password),
            'role' => 'Etudiant',
            'is_active' => true,
        ]
        );

        // Création du profil étudiant
        Etudiant::updateOrCreate(
        ['user_id' => $user->id],
        [
            'nom' => 'DOE',
            'prenom' => 'John',
            'date_naissance' => '2000-01-01',
            'sexe' => 'Masculin',
            'annee_obtention_bac' => 2018,
            'profil_complet' => false,
        ]
        );

        $this->command->info("Étudiant local créé : {$email} / {$password}");
    }
}