<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Mokpokpo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/dist/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 flex flex-col min-h-screen">
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    <div class="bg-blue-600 p-2 rounded-lg text-white">
                        <i class="fas fa-university text-xl"></i>
                    </div>
                    <span class="text-xl font-bold text-gray-900 tracking-tight">Espace Étudiant</span>
                </div>
                <div>
                    <div class="flex items-center gap-4">
                        <span class="text-gray-700 font-medium">{{ $user->email }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="bg-red-50 text-red-600 px-4 py-2 rounded-lg font-semibold hover:bg-red-100 transition text-sm">
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
        <div
            class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-3">
            <i class="fas fa-check-circle text-green-500"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="p-6 sm:p-8 bg-blue-600 text-white">
                <h1 class="text-3xl font-bold mb-2">Bienvenue dans votre espace !</h1>
                <p class="text-blue-100">Gérez vos demandes de logement et suivez l'évolution de votre dossier.</p>
            </div>

            <div class="p-6 sm:p-8 grid md:grid-cols-2 gap-8">
                <!-- Profile Status -->
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-user-circle text-blue-500"></i>
                        Mon Profil
                    </h2>
                    <ul class="space-y-3 text-sm text-gray-600 mb-6">
                        <li class="flex justify-between items-center">
                            <span class="font-medium">Identifiant :</span>
                            <span class="text-gray-900">{{ $user->email }}</span>
                        </li>
                        @if($user->etudiant)
                        <li class="flex justify-between items-center border-t border-gray-100 pt-2">
                            <span class="font-medium">Nom complet :</span>
                            <span class="text-gray-900">{{ $user->etudiant->nom }} {{ $user->etudiant->prenom }}</span>
                        </li>
                        <li class="flex justify-between items-center">
                            <span class="font-medium">Date du BAC :</span>
                            <span class="text-gray-900">{{ $user->etudiant->date_obtention_bac ?
                                $user->etudiant->date_obtention_bac->format('d/m/Y') : 'Non renseigné' }}</span>
                        </li>
                        <li class="flex justify-between items-center">
                            <span class="font-medium">Mobilité/Handicap :</span>
                            <div class="flex flex-wrap gap-1 justify-end">
                                @forelse($user->etudiant->handicaps as $h)
                                <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded text-[10px] font-bold">{{
                                    $h->nom }}</span>
                                @empty
                                <span class="text-gray-400">Aucun</span>
                                @endforelse
                            </div>
                        </li>
                        @endif
                        <li class="flex justify-between items-center border-t border-gray-100 pt-2">
                            <span class="font-medium">Statut :</span>
                            @if($user->etudiant && $user->etudiant->profil_complet)
                            <span
                                class="bg-green-100 text-green-800 py-0.5 px-3 rounded-full text-xs font-bold flex items-center gap-1">
                                <i class="fas fa-check-circle"></i> Complet
                            </span>
                            @else
                            <span
                                class="bg-yellow-100 text-yellow-800 py-0.5 px-3 rounded-full text-xs font-bold flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> Incomplet
                            </span>
                            @endif
                        </li>
                    </ul>

                    @if(!$user->etudiant || !$user->etudiant->profil_complet)
                    <a href="{{ route('profile.complete') }}"
                        class="block text-center w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition shadow-sm">
                        Compléter mon profil
                    </a>
                    @else
                    <a href="{{ route('profile.complete') }}"
                        class="block text-center w-full bg-white border border-gray-300 text-gray-700 py-2 rounded-lg font-semibold hover:bg-gray-50 transition shadow-sm">
                        Modifier mon profil
                    </a>
                    @endif
                </div>

                <!-- Applications Status -->
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-home text-blue-500"></i>
                        Mes Demandes
                    </h2>

                    @php
                    $demande = $user->etudiant ? $user->etudiant->demandeLogements()->latest()->first() : null;
                    @endphp

                    @if($demande)
                    <div class="bg-white p-4 rounded-xl border border-gray-200 mb-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Demande du {{
                                    $demande->date_soumission->format('d/m/Y') }}</span>
                                <h4 class="font-bold text-gray-800">{{ $demande->type_logement ?
                                    $demande->type_logement->nom : 'Type non spécifié' }}</h4>
                            </div>
                            @php
                            $statusClasses = [
                            'En attente' => 'bg-yellow-100 text-yellow-800',
                            'En cours' => 'bg-blue-100 text-blue-800',
                            'Validée' => 'bg-green-100 text-green-800',
                            'Rejetée' => 'bg-red-100 text-red-800',
                            ];
                            $class = $statusClasses[$demande->statut] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span
                                class="{{ $class }} py-0.5 px-2 rounded text-[10px] font-bold uppercase tracking-wide">
                                {{ $demande->statut }}
                            </span>
                        </div>

                        <div class="space-y-2 text-xs text-gray-600">
                            <div class="flex justify-between">
                                <span>Bâtiment :</span>
                                <span class="font-medium text-gray-800">{{ $demande->batiment ? $demande->batiment->nom
                                    : 'Aucun' }}</span>
                            </div>
                            @if($demande->logement_propose)
                            <div class="flex justify-between pt-2 border-t border-gray-50">
                                <span>Logement attribué :</span>
                                <span class="font-bold text-blue-600">{{ $demande->logement_propose->numero_chambre
                                    }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @else
                    <div class="text-center py-6 text-gray-500">
                        <i class="fas fa-folder-open text-4xl mb-3 text-gray-300"></i>
                        <p class="text-sm">Vous n'avez aucune demande de logement en cours.</p>
                    </div>
                    @endif

                    @if(!$demande || in_array($demande->statut, ['Rejetée']))
                    <a href="{{ route('demandes.create') }}"
                        class="block text-center w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition shadow-sm mt-2">
                        Faire une demande
                    </a>
                    @else
                    <button disabled
                        class="w-full bg-gray-100 text-gray-400 py-2 rounded-lg font-semibold cursor-not-allowed mt-2">
                        Demande en cours
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center text-sm text-gray-500">
            <p>&copy; 2026 Mokpokpo Université. Tableau de bord étudiant.</p>
        </div>
    </footer>
</body>

</html>