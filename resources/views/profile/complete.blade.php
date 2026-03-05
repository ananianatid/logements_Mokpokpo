@extends('layouts.student')

@section('title', 'Compléter mon profil - Mokpokpo')

@section('content')
<div class="flex items-center justify-center p-6 py-12">
    <div class="glass-panel w-full max-w-2xl rounded-2xl overflow-hidden shadow-sm">

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
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition uppercase"
                            readonly>
                    </div>

                    <!-- Prénom -->
                    <div>
                        <label for="prenom" class="block text-sm font-semibold text-gray-700 mb-2">Prénom(s) <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="prenom" id="prenom"
                            value="{{ old('prenom', $etudiant->prenom !== 'inconnu' ? $etudiant->prenom : '') }}"
                            required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition capitalize"
                            readonly>
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
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition text-gray-600"
                            readonly>
                    </div>

                    <!-- Sexe -->
                    <div>
                        <label for="sexe" class="block text-sm font-semibold text-gray-700 mb-2">Sexe <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="sexe" id="sexe" value="{{ old('sexe', $etudiant->sexe) }}" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition text-gray-600"
                            readonly>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Année d'obtention du BAC -->
                    <div>
                        <label for="annee_obtention_bac" class="block text-sm font-semibold text-gray-700 mb-2">Année
                            d'obtention de votre baccalauréat</label>
                        <input type="number" name="annee_obtention_bac" id="annee_obtention_bac" min="1900"
                            max="{{ date('Y') + 1 }}"
                            value="{{ old('annee_obtention_bac', $etudiant->annee_obtention_bac) }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-gray-600"
                            readonly>
                    </div>

                    <!-- Moyenne au BAC -->
                    <div>
                        <label for="moyenne_bac" class="block text-sm font-semibold text-gray-700 mb-2">Moyenne au
                            BAC</label>
                        <input type="number" name="moyenne_bac" id="moyenne_bac" step="0.01" min="0" max="20"
                            value="{{ old('moyenne_bac', $etudiant->moyenne_bac) }}" placeholder="00.00"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition text-gray-600"
                            readonly>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-1">Facultatif, mais recommandé pour l'évaluation de votre
                    dossier.</p>

                {{-- Préfecture d'origine --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Région --}}
                    <div>
                        <label for="region_select" class="block text-sm font-semibold text-gray-700 mb-2">
                            Région d'origine
                        </label>
                        <select id="region_select"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition bg-white appearance-none">
                            <option value="">-- Sélectionnez une région --</option>
                            @foreach(array_keys($prefecturesParRegion) as $region)
                            <option value="{{ $region }}" {{ (old('prefecture_origine', $etudiant->
                                prefecture_origine ?? '') && str_contains(old('prefecture_origine',
                                $etudiant->prefecture_origine ?? ''), '')) ? '' : '' }}
                                data-region="{{ $region }}">
                                {{ $region }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Préfecture --}}
                    <div>
                        <label for="prefecture_origine" class="block text-sm font-semibold text-gray-700 mb-2">
                            Préfecture d'origine
                        </label>
                        <select name="prefecture_origine" id="prefecture_origine"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition bg-white appearance-none">
                            <option value="">-- Choisissez d'abord une région --</option>
                            @foreach($prefecturesParRegion as $region => $prefectures)
                            @foreach($prefectures as $prefecture)
                            <option value="{{ $prefecture }}" data-region="{{ $region }}" {{ old('prefecture_origine',
                                $etudiant->prefecture_origine ?? '') == $prefecture ?
                                'selected' : '' }}>
                                {{ $prefecture }}
                            </option>
                            @endforeach
                            @endforeach
                        </select>
                    </div>
                </div>

                <script>
                    (function () {
                        const regionSelect = document.getElementById('region_select');
                        const prefSelect = document.getElementById('prefecture_origine');
                        const allOptions = Array.from(prefSelect.options).slice(1); // skip placeholder

                        // Detect current region from selected prefecture
                        const currentPref = prefSelect.value;
                        let currentRegion = '';
                        if (currentPref) {
                            const selectedOpt = allOptions.find(o => o.value === currentPref);
                            if (selectedOpt) currentRegion = selectedOpt.dataset.region;
                        }

                        function filterPrefectures(selectedRegion) {
                            // Reset
                            while (prefSelect.options.length > 1) prefSelect.remove(1);

                            if (!selectedRegion) {
                                prefSelect.options[0].text = '-- Choisissez d\'abord une région --';
                                return;
                            }
                            prefSelect.options[0].text = '-- Sélectionnez une préfecture --';

                            allOptions
                                .filter(o => o.dataset.region === selectedRegion)
                                .forEach(o => {
                                    const clone = o.cloneNode(true);
                                    if (clone.value === currentPref && selectedRegion === currentRegion) {
                                        clone.selected = true;
                                    }
                                    prefSelect.appendChild(clone);
                                });
                        }

                        // Init
                        if (currentRegion) {
                            regionSelect.value = currentRegion;
                            filterPrefectures(currentRegion);
                        }

                        regionSelect.addEventListener('change', function () {
                            currentRegion = '';
                            currentPref && (currentPref = '');
                            filterPrefectures(this.value);
                        });
                    })();
                </script>

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
</div>
@endsection