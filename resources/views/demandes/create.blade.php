<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de Logement - Mokpokpo</title>
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

        .selection-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .selection-card:hover {
            transform: translateY(-2px);
        }

        input[type="radio"]:checked+.selection-card {
            border-color: #2563eb;
            background-color: #eff6ff;
            ring: 2px;
            ring-color: #3b82f6;
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
        <div class="glass-panel w-full max-w-3xl rounded-2xl overflow-hidden">

            <div class="bg-blue-600 p-8 text-white text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white/20 mb-4">
                    <i class="fas fa-home text-3xl"></i>
                </div>
                <h1 class="text-3xl font-bold mb-2">Nouvelle demande de logement</h1>
                <p class="text-blue-100 opacity-90">Choisissez vos préférences pour votre futur logement sur le campus.
                </p>
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

                <form method="POST" action="{{ route('demandes.store') }}" class="space-y-10">
                    @csrf

                    <!-- Section Bâtiment -->
                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                                1</div>
                            <h2 class="text-xl font-bold text-gray-800">Sélectionnez le bâtiment</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($batiments as $batiment)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="batiment_id" value="{{ $batiment->id }}" class="sr-only"
                                    required>
                                <div
                                    class="selection-card h-full p-5 rounded-xl border-2 border-gray-100 bg-gray-50 flex flex-col items-center text-center">
                                    <i
                                        class="fas fa-building text-2xl mb-3 text-gray-400 group-checked:text-blue-600"></i>
                                    <span class="font-bold text-sm text-gray-800">{{ $batiment->nom }}</span>
                                    <span class="text-[10px] text-gray-500 mt-1 uppercase tracking-wider">{{
                                        $batiment->adresse }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Section Type de Logement -->
                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                                2</div>
                            <h2 class="text-xl font-bold text-gray-800">Choisissez le type de chambre</h2>
                        </div>

                        <div class="grid grid-cols-1 gap-4">
                            @foreach($typeLogements as $type)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="type_logement_id" value="{{ $type->id }}" class="sr-only"
                                    required>
                                <div
                                    class="selection-card p-5 rounded-xl border-2 border-gray-100 bg-gray-50 flex items-start gap-4">
                                    <div class="bg-white p-3 rounded-lg shadow-sm">
                                        <i class="fas fa-bed text-xl text-blue-500"></i>
                                    </div>
                                    <div class="flex-grow">
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="font-bold text-gray-800">{{ $type->nom }}</span>
                                            <span class="text-blue-600 font-bold">{{ number_format($type->prix, 0, ',',
                                                ' ') }} FCFA <span class="text-[10px] text-gray-400 font-normal">/
                                                    mois</span></span>
                                        </div>
                                        <p class="text-sm text-gray-500 leading-relaxed">{{ $type->caracteristique }}
                                        </p>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Recap and Warning -->
                    <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 flex gap-4">
                        <i class="fas fa-info-circle text-amber-500 text-xl mt-1"></i>
                        <div>
                            <h4 class="font-bold text-amber-800 text-sm">Note importante</h4>
                            <p class="text-xs text-amber-700 leading-relaxed mt-1">
                                Votre demande sera examinée par l'administration. Le choix du bâtiment et du type de
                                chambre est indicatif et dépend des disponibilités réelles. Les étudiants boursiers ou
                                en situation de handicap sont prioritaires.
                            </p>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-6 border-t border-gray-100">
                        <button type="submit"
                            class="w-full flex justify-center items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl transition duration-200 shadow-lg shadow-blue-500/30">
                            <i class="fas fa-paper-plane"></i>
                            Soumettre ma demande
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer class="p-8 text-center text-gray-400 text-sm">
        &copy; 2026 Mokpokpo Université. Service des Logements Étudiants.
    </footer>

</body>

</html>