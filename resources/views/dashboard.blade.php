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

                <!-- Applications / Housing Status -->
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-home text-blue-500"></i>
                        @if($contrat && $contrat->statut === 'Actif')
                        Mon Logement
                        @else
                        Mes Demandes
                        @endif
                    </h2>

                    @if($contrat && $contrat->statut === 'Actif')
                    <!-- ACTIVE RESIDENT VIEW -->
                    <div class="space-y-4">
                        <div class="bg-blue-600 text-white p-4 rounded-xl shadow-sm">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-bold uppercase opacity-80">Chambre</span>
                                <span class="bg-white text-blue-600 px-2 py-0.5 rounded text-xs font-bold">Actif</span>
                            </div>
                            <h3 class="text-2xl font-bold">{{ $contrat->logement->nomenclature }}</h3>
                            <p class="text-sm opacity-90">{{ $contrat->logement->batiment->nom }} - {{
                                $contrat->logement->type_logement->nom }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <button onclick="document.getElementById('incidentModal').classList.remove('hidden')"
                                class="flex flex-col items-center justify-center p-3 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                                <i class="fas fa-tools text-orange-500 mb-1"></i>
                                <span class="text-[10px] font-bold text-gray-700">Signaler un Pb</span>
                            </button>
                            <button onclick="document.getElementById('paymentModal').classList.remove('hidden')"
                                class="flex flex-col items-center justify-center p-3 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                                <i class="fas fa-file-invoice-dollar text-green-500 mb-1"></i>
                                <span class="text-[10px] font-bold text-gray-700">Mes Paiements</span>
                            </button>
                        </div>

                        @if($incidents->count() > 0)
                        <div class="mt-4">
                            <h4 class="text-xs font-bold text-gray-400 uppercase mb-2">Incidents récents</h4>
                            <div class="space-y-2">
                                @foreach($incidents as $incident)
                                <div
                                    class="text-[10px] p-2 bg-white border border-gray-100 rounded flex justify-between items-center">
                                    <span class="text-gray-700">{{ $incident->type }} : {{
                                        Str::limit($incident->description, 20) }}</span>
                                    <span
                                        class="px-1.5 py-0.5 rounded-full font-bold {{ $incident->statut === 'Résolu' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                                        {{ $incident->statut }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    @elseif($contrat && $activationProgress)
                    <!-- FUTURE RESIDENT / PENDING ACTIVATION -->
                    <div class="bg-white p-5 rounded-xl border border-blue-100 shadow-sm">
                        <h4 class="font-bold text-blue-900 text-sm mb-3">Activation de votre logement</h4>

                        <div class="mb-4">
                            <div class="flex justify-between text-[10px] font-bold text-gray-500 mb-1">
                                <span>Progression</span>
                                <span>{{ $activationProgress['percentage'] }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-1.5">
                                <div class="bg-blue-600 h-1.5 rounded-full transition-all duration-500"
                                    style="width: {{ $activationProgress['percentage'] }}%"></div>
                            </div>
                        </div>

                        <ul class="space-y-3 text-[10px]">
                            @foreach($activationProgress['steps'] as $key => $step)
                            <li class="flex flex-col gap-1">
                                <div class="flex items-center gap-2">
                                    <i
                                        class="fas {{ $step['done'] ? 'fa-check-circle text-green-500' : 'fa-circle text-gray-200' }}"></i>
                                    <span class="{{ $step['done'] ? 'text-gray-700 font-medium' : 'text-gray-400' }}">
                                        {{ $step['label'] }}
                                    </span>
                                    @if($key === 'payments' && !$step['done'])
                                    <span class="ml-auto bg-gray-100 px-1.5 rounded font-bold">{{ $step['count'] }}/{{
                                        $step['required'] }}</span>
                                    @endif
                                </div>
                                @if(!$step['done'] && isset($step['date_rendez_vous']) && $step['date_rendez_vous'])
                                <div
                                    class="ml-5 flex items-center gap-1.5 text-blue-600 bg-blue-50 px-2 py-1 rounded w-fit font-semibold">
                                    <i class="fas fa-calendar-alt text-[9px]"></i>
                                    <span>RDV : {{ \Carbon\Carbon::parse($step['date_rendez_vous'])->translatedFormat('d
                                        F à H:i') }}</span>
                                </div>
                                @endif
                            </li>
                            @endforeach
                        </ul>

                        @if($activationProgress['percentage'] < 100) <div
                            class="mt-4 p-2 bg-yellow-50 border border-yellow-100 rounded text-[9px] text-yellow-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            Votre chambre sera activée dès que ces étapes seront complétées.
                    </div>
                    @endif
                </div>

                @elseif($demande)
                <!-- APPLICANT VIEW -->
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
                        <span class="{{ $class }} py-0.5 px-2 rounded text-[10px] font-bold uppercase tracking-wide">
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
                            <span class="font-bold text-blue-600">{{ $demande->logement_propose->nomenclature
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
                @elseif($demande && $demande->statut === 'Validée' && !$contrat)
                <div class="mt-4 p-3 bg-green-50 border border-green-100 rounded-lg text-xs text-green-800">
                    <p class="font-bold mb-1"><i class="fas fa-calendar-check mr-1"></i> Félicitations !</p>
                    Votre demande est validée. Un agent administratif va bientôt générer votre contrat d'habitation.
                </div>
                @endif
            </div>
        </div>
        </div>
    </main>

    <!-- Incident Modal -->
    <div id="incidentModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl max-w-md w-full overflow-hidden shadow-2xl">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900">Signaler un incident</h3>
                <button onclick="document.getElementById('incidentModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('incidents.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                @if($contrat)
                <input type="hidden" name="logement_id" value="{{ $contrat->logement_id }}">
                @endif
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Type d'incident</label>
                    <select name="type"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                        required>
                        <option value="Panne">Panne (Electricité, Plomberie...)</option>
                        <option value="Dégât">Dégât matériel</option>
                        <option value="Voisinage">Voisinage (Bruit, comportement...)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="4"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                        placeholder="Décrivez le problème avec précision..." required></textarea>
                </div>
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition">
                    Envoyer le signalement
                </button>
            </form>
        </div>
    </div>

    <!-- Payment History Modal -->
    <div id="paymentModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl max-w-md w-full overflow-hidden shadow-2xl">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="text-xl font-bold text-gray-900">Historique des Paiements</h3>
                <button onclick="document.getElementById('paymentModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 max-h-[60vh] overflow-y-auto">
                @if($paymentHistory && count($paymentHistory) > 0)
                <div class="space-y-3">
                    @foreach($paymentHistory as $item)
                    <div
                        class="flex items-center justify-between p-3 rounded-xl border {{ $item['statut'] === 'En retard' ? 'bg-red-50 border-red-100' : 'bg-white border-gray-100 shadow-sm' }}">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-gray-900">{{ $item['month'] }}</span>
                            <span class="text-[10px] text-gray-500">Loyer mensuel : {{
                                number_format($item['montant'], 0, ',', ' ') }} CFA</span>
                        </div>
                        <div class="flex flex-col items-end">
                            <span
                                class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $item['statut'] === 'En retard' ? 'bg-red-600 text-white' : ($item['statut'] === 'Payé' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ $item['statut'] }}
                            </span>
                            @if(isset($item['note']))
                            <span class="text-[8px] font-bold text-gray-400 mt-0.5">{{ $item['note'] }}</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-receipt text-4xl mb-3 text-gray-300"></i>
                    <p class="text-sm">Aucun historique disponible.</p>
                </div>
                @endif
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-100 flex flex-col gap-3 items-center">
                <button onclick="document.getElementById('paymentModal').classList.add('hidden')"
                    class="w-full bg-blue-600 text-white py-2 rounded-xl font-bold hover:bg-blue-700 transition shadow-sm">
                    Fermer
                </button>
                <p class="text-[10px] text-gray-500">Pour tout litige, veuillez contacter le service comptable.</p>
            </div>
        </div>
    </div>

    <footer class="bg-white border-t border-gray-200 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center text-sm text-gray-500">
            <p>&copy; 2026 Mokpokpo Université. Tableau de bord étudiant.</p>
        </div>
    </footer>
</body>

</html>