<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compléter mon profil - Mokpokpo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/dist/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }
    </style>
</head>

<body class="flex flex-col min-h-screen text-gray-800">

    <!-- Navbar -->
    <nav class="bg-white px-6 py-4 border-b border-gray-200">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-2 text-gray-800 hover:text-blue-600 transition">
                <i class="fas fa-arrow-left"></i>
                <span class="font-semibold">Retour au tableau de bord</span>
            </a>
            <div class="flex items-center gap-2">
                <div class="bg-blue-600 p-2 rounded-lg text-white">
                    <i class="fas fa-university text-lg"></i>
                </div>
                <span class="font-bold text-xl tracking-tight">Mokpokpo</span>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center p-6 py-12">
        <div class="glass-panel w-full max-w-2xl rounded-2xl overflow-hidden">

            <div class="bg-blue-600 p-8 text-white text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white/20 mb-4">
                    <i class="fas fa-user-edit text-3xl"></i>
                </div>
                <h1 class="text-3xl font-bold mb-2">Complétez votre profil</h1>
                <p class="text-blue-100 opacity-90">Ces informations sont nécessaires pour traiter vos futures demandes
                    de logement.</p>
            </div>

            <div class="p-8">
                @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-600">
                    <ul class="list-disc pl-5 space-y-1 text-sm font-medium">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if (session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-medium">
                    {{ session('success') }}
                </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nom -->
                        <div>
                            <label for="nom" class="block text-sm font-semibold text-gray-700 mb-2">Nom de famille <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="nom" id="nom"
                                value="{{ old('nom', $etudiant->nom !== 'inconnu' ? $etudiant->nom : '') }}" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition uppercase">
                        </div>

                        <!-- Prénom -->
                        <div>
                            <label for="prenom" class="block text-sm font-semibold text-gray-700 mb-2">Prénom(s) <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="prenom" id="prenom"
                                value="{{ old('prenom', $etudiant->prenom !== 'inconnu' ? $etudiant->prenom : '') }}"
                                required
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition capitalize">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Date de naissance -->
                        <div>
                            <label for="date_naissance" class="block text-sm font-semibold text-gray-700 mb-2">Date de
                                naissance <span class="text-red-500">*</span></label>
                            <input type="date" name="date_naissance" id="date_naissance"
                                value="{{ old('date_naissance', $etudiant->date_naissance ? $etudiant->date_naissance->format('Y-m-d') : '') }}"
                                required
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition text-gray-600">
                        </div>

                        <!-- Sexe -->
                        <div>
                            <label for="sexe" class="block text-sm font-semibold text-gray-700 mb-2">Sexe <span
                                    class="text-red-500">*</span></label>
                            <select name="sexe" id="sexe" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition bg-white appearance-none">
                                <option value="" disabled selected>Sélectionnez...</option>
                                <option value="Masculin" {{ old('sexe', $etudiant->sexe) == 'Masculin' ? 'selected' : ''
                                    }}>Masculin</option>
                                <option value="Feminin" {{ old('sexe', $etudiant->sexe) == 'Feminin' ? 'selected' : ''
                                    }}>Féminin</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Date d'obtention du BAC -->
                        <div>
                            <label for="date_obtention_bac" class="block text-sm font-semibold text-gray-700 mb-2">Année
                                /
                                Date d'obtention du BAC</label>
                            <input type="date" name="date_obtention_bac" id="date_obtention_bac"
                                value="{{ old('date_obtention_bac', $etudiant->date_obtention_bac ? $etudiant->date_obtention_bac->format('Y-m-d') : '') }}"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition text-gray-600">
                        </div>

                        <!-- Moyenne au BAC -->
                        <div>
                            <label for="moyenne_bac" class="block text-sm font-semibold text-gray-700 mb-2">Moyenne au
                                BAC</label>
                            <input type="number" name="moyenne_bac" id="moyenne_bac" step="0.01" min="0" max="20"
                                value="{{ old('moyenne_bac', $etudiant->moyenne_bac) }}" placeholder="00.00"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition text-gray-600">
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Facultatif, mais recommandé pour l'évaluation de votre
                        dossier.</p>

                    <!-- Adresse Actuelle -->
                    <div>
                        <label for="adresse_actuelle" class="block text-sm font-semibold text-gray-700 mb-2">Adresse
                            actuelle complète</label>
                        <textarea name="adresse_actuelle" id="adresse_actuelle" rows="3"
                            placeholder="Quartier, Ville, Pays..."
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition resize-y">{{ old('adresse_actuelle', $etudiant->adresse_actuelle) }}</textarea>
                    </div>

                    <!-- Situation Matrimoniale -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="situation_matrimoniale"
                                class="block text-sm font-semibold text-gray-700 mb-2">Situation matrimoniale</label>
                            <select name="situation_matrimoniale" id="situation_matrimoniale"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition bg-white appearance-none">
                                <option value="" disabled selected>Sélectionnez...</option>
                                <option value="Celibataire" {{ old('situation_matrimoniale', $etudiant->
                                    situation_matrimoniale) == 'Celibataire' ? 'selected' : '' }}>Célibataire</option>
                                <option value="Marie" {{ old('situation_matrimoniale', $etudiant->
                                    situation_matrimoniale) ==
                                    'Marie' ? 'selected' : '' }}>Marié(e)</option>
                                <option value="Divorce" {{ old('situation_matrimoniale', $etudiant->
                                    situation_matrimoniale)
                                    == 'Divorce' ? 'selected' : '' }}>Divorcé(e)</option>
                                <option value="Veuf" {{ old('situation_matrimoniale', $etudiant->situation_matrimoniale)
                                    ==
                                    'Veuf' ? 'selected' : '' }}>Veuf/Veuve</option>
                            </select>
                        </div>

                        <!-- Handicap -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Handicap(s)
                                éventuel(s)</label>
                            <div class="grid grid-cols-2 gap-2 bg-white p-3 rounded-xl border border-gray-300">
                                @foreach($handicaps as $handicap)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" name="handicaps[]" value="{{ $handicap->id }}" {{
                                        in_array($handicap->id, old('handicaps', $selectedHandicaps ?? [])) ? 'checked'
                                    : '' }}
                                    class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-600 group-hover:text-blue-600 transition">{{
                                        $handicap->nom }}</span>
                                </label>
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Cochez les options qui s'appliquent à vous.</p>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4 border-t border-gray-100">
                        <button type="submit"
                            class="w-full flex justify-center items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl transition duration-200 shadow-lg shadow-blue-500/30">
                            <i class="fas fa-save"></i>
                            Enregistrer mon profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

</body>

</html>