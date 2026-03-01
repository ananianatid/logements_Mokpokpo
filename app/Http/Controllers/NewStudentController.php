<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Etudiant;

class NewStudentController extends Controller
{
    public function show()
    {
        return view('new-student');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'prenom' => ['required', 'string', 'max:100'],
            'nom' => ['required', 'string', 'max:100'],
        ]);

        $prenom = $validated['prenom'];
        $nom = $validated['nom'];

        // Normalise: lowercase, remove accents, spaces → dots
        $prenomSlug = $this->slugify($prenom);
        $nomSlug = $this->slugify($nom);

        // Generate email: prenom.nom@etudiant.mokpokpo.edu
        $baseEmail = "{$prenomSlug}.{$nomSlug}@etudiant.mokpokpo.edu";
        $email = $this->ensureUniqueEmail($baseEmail);

        // Generate a readable password
        $plainPassword = Str::upper(substr($prenomSlug, 0, 3))
            . rand(100, 999)
            . '!'
            . ucfirst(substr($nomSlug, 0, 3));

        // Create the User
        $user = User::create([
            'email' => $email,
            'password' => Hash::make($plainPassword),
            'role' => 'Etudiant',
            'is_active' => true,
        ]);

        // Create the associated Etudiant profile
        Etudiant::create([
            'user_id' => $user->id,
            'nom' => strtoupper($nom),
            'prenom' => ucfirst(strtolower($prenom)),
            'date_naissance' => now()->subYears(20)->toDateString(),
            'sexe' => 'Masculin',
            'profil_complet' => false,
        ]);

        return back()->with([
            'generated_email' => $email,
            'generated_password' => $plainPassword,
            'student_name' => ucfirst(strtolower($prenom)) . ' ' . strtoupper($nom),
        ]);
    }

    /**
     * Converts a string to a URL-safe ASCII slug (handles French accents).
     */
    private function slugify(string $value): string
    {
        $value = mb_strtolower($value, 'UTF-8');

        // Transliterate French accents
        $map = [
            'à' => 'a', 'â' => 'a', 'ä' => 'a', 'á' => 'a',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e',
            'î' => 'i', 'ï' => 'i', 'í' => 'i',
            'ô' => 'o', 'ö' => 'o', 'ó' => 'o',
            'ù' => 'u', 'û' => 'u', 'ü' => 'u', 'ú' => 'u',
            'ç' => 'c', 'ñ' => 'n',
        ];

        $value = strtr($value, $map);

        // Keep only alphanumeric chars, replace spaces/hyphens with nothing
        $value = preg_replace('/[^a-z0-9]/', '', $value);

        return $value;
    }

    /**
     * Ensures the email is unique by appending a counter if needed.
     */
    private function ensureUniqueEmail(string $base): string
    {
        if (!User::where('email', $base)->exists()) {
            return $base;
        }

        [$local, $domain] = explode('@', $base);
        $counter = 1;

        while (User::where('email', "{$local}{$counter}@{$domain}")->exists()) {
            $counter++;
        }

        return "{$local}{$counter}@{$domain}";
    }
}