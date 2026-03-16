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

        // Authentification standard
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard')->with('success', 'Bon de retour !');
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