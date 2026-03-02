<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Etudiant;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $email = $credentials['email'];

        // Si l'email se termine par @defitech.tg, on interroge l'API
        if (Str::endsWith($email, '@defitech.tg')) {
            try {
                $apiUrl = env('DEFITECH_API_URL', 'http://localhost:8000/api');
                $response = Http::post($apiUrl . '/students/verify', [
                    'email' => $email,
                    'password' => $credentials['password'],
                ]);

                if ($response->successful()) {
                    $data = $response->json('data');

                    // Récupération ou création de l'User
                    $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'password' => Hash::make($credentials['password']),
                        'role' => 'Etudiant',
                        'is_active' => true,
                    ]
                    );

                    // Mise à jour du mot de passe en local au cas où il a changé sur la plateforme principale
                    $user->update([
                        'password' => Hash::make($credentials['password'])
                    ]);

                    $sexe = 'Masculin';
                    if (isset($data['gender'])) {
                        $sexe = $data['gender'] === 'F' ? 'Feminin' : 'Masculin';
                    }

                    $anneeObtentionBac = null;
                    if (isset($data['bac_year'])) {
                        $anneeObtentionBac = $data['bac_year'];
                    }

                    $photoPath = null;
                    if (isset($data['photo_path'])) {
                        $photoPath = $data['photo_path'];
                    }

                    $birthDate = now()->subYears(18)->toDateString();
                    if (!empty($data['birth_date'])) {
                        $birthDate = date('Y-m-d', strtotime($data['birth_date']));
                    }

                    // Création ou mise à jour du profil Étudiant
                    Etudiant::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nom' => $data['last_name'] ?? 'Inconnu',
                        'prenom' => $data['first_names'] ?? 'Inconnu',
                        'date_naissance' => $birthDate,
                        'sexe' => $sexe,
                        'annee_obtention_bac' => $anneeObtentionBac,
                        'photo_path' => $photoPath,
                        'adresse_actuelle' => $data['address'] ?? null,
                        'profil_complet' => false,
                    ]
                    );

                    Auth::login($user);
                    $request->session()->regenerate();

                    return redirect()->intended('/dashboard')->with('success', 'Bienvenue de retour !');
                }
                else {
                    return back()->withErrors([
                        'email' => 'Identifiants incorrects ou compte non trouvé sur la plateforme Defitech.',
                    ])->onlyInput('email');
                }
            }
            catch (\Exception $e) {
                return back()->withErrors([
                    'email' => 'Impossible de contacter le serveur d\'authentification étudiant. Veuillez réessayer plus tard.',
                ])->onlyInput('email');
            }
        }

        // Pour tous les autres emails (le personnel avec @mokpokpo.tg)
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard')->with('success', 'Bienvenue de retour !');
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['required', 'accepted'],
        ]);

        // Simplification: We extract a "nom" and "prenom" from the email for the DB
        $emailParts = explode('@', $validated['email']);
        $namePart = $emailParts[0] ?? 'Etudiant'; // everything before @

        // We'll just split it roughly in half if we can for fake names
        $half = (int)(strlen($namePart) / 2);
        $nom = ucfirst(substr($namePart, 0, $half));
        $prenom = ucfirst(substr($namePart, $half));

        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'Etudiant',
            'is_active' => true,
        ]);

        // Create the associated empty student profile
        Etudiant::create([
            'user_id' => $user->id,
            'nom' => $nom ?: 'inconnu',
            'prenom' => $prenom ?: 'inconnu',
            'date_naissance' => now()->subYears(18)->toDateString(),
            'sexe' => 'Masculin', // Or ask in form, hardcoding for now
            'profil_complet' => false,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Votre compte a bien été créé !');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Vous êtes déconnecté.');
    }
}