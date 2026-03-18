<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mokpokpo Université - Logements Étudiants</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>

        body {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        .glass-morphism {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .hero-gradient {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        }

        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }

        /* Step connector line */
        .step-connector {
            position: absolute;
            top: 28px;
            left: calc(50% + 28px);
            right: calc(-50% + 28px);
            height: 2px;
            background: linear-gradient(90deg, #3b82f6, #a5b4fc);
        }

        @media (max-width: 1023px) {
            .step-connector {
                display: none;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 glass-morphism shadow-sm" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2 hover:opacity-90 transition">
                    <div class="bg-blue-600 p-2 rounded-lg text-white">
                        <i class="fas fa-university text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold text-blue-900 tracking-tight">Mokpokpo</span>
                </a>
                <div class="hidden md:flex space-x-8 text-gray-600 font-medium">
                    <!-- <a href="#processus" class="hover:text-blue-600 transition">Comment ça marche</a> -->
                    <a href="{{route('residences.index')}}" class="hover:text-blue-600 transition">Résidences</a>
                    <!-- <a href="#faq" class="hover:text-blue-600 transition">FAQ</a> -->
                </div>
                <div class="hidden md:flex">
                    @auth
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open"
                            class="flex items-center gap-2 p-1 rounded-xl hover:bg-gray-100 transition duration-200">
                            <div
                                class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center border border-blue-200 shadow-sm">
                                <i class="fas fa-user-graduate text-lg"></i>
                            </div>
                            <i class="fas fa-chevron-down text-[10px] text-gray-400"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50 overflow-hidden"
                            style="display: none;">

                            <div class="px-4 py-3 border-b border-gray-50 mb-2 bg-gray-50/50">
                                <div class="flex items-center gap-2 mb-1">
                                    <span
                                        class="text-[10px] font-bold text-blue-600 uppercase tracking-widest bg-blue-50 px-2 py-0.5 rounded-full">
                                        {{ auth()->user()->role }}
                                    </span>
                                </div>
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->email }}</p>
                            </div>

                            @if(auth()->user()->role === 'Etudiant')
                            <a href="{{ route('dashboard') }}"
                                class="flex items-center gap-3 px-4 py-3 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition">
                                <i class="fas fa-tachometer-alt w-5 text-gray-400"></i>
                                Tableau de bord
                            </a>
                            <a href="{{ route('profile.complete') }}"
                                class="flex items-center gap-3 px-4 py-3 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition">
                                <i class="fas fa-user-circle w-5 text-gray-400"></i>
                                Mon profil
                            </a>
                            @elseif(in_array(auth()->user()->role, ['Admin', 'Administratif', 'Comptable', 'Concierge',
                            'Technicien']))
                            <a href="/admin"
                                class="flex items-center gap-3 px-4 py-3 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition">
                                <i class="fas fa-shield-alt w-5 text-gray-400"></i>
                                Panel Administration
                            </a>
                            @endif

                            <div class="border-t border-gray-50 mt-2 pt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition font-medium">
                                        <i class="fas fa-sign-out-alt w-5"></i>
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @else
                    <button onclick="toggleModal('login-modal')"
                        class="bg-blue-600 text-white px-6 py-2 rounded-full font-semibold hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                        Se connecter
                    </button>
                    @endauth
                </div>
                <div class="-mr-2 flex items-center md:hidden">
                    <button type="button" @click="mobileMenuOpen = !mobileMenuOpen"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile menu -->
        <div class="md:hidden" x-show="mobileMenuOpen" x-collapse>
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <!-- <a href="#processus" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50">Comment ça marche</a> -->
                <a href="{{route('residences.index')}}"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50">Résidences</a>
            </div>
            <div class="pt-4 pb-3 border-t border-gray-200 px-2 space-y-1">
                @auth
                <div class="px-3 py-3 bg-gray-50 rounded-xl mb-2">
                    <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mb-1">{{
                        auth()->user()->role }}</p>
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->email }}</p>
                </div>

                @if(auth()->user()->role === 'Etudiant')
                <a href="{{ route('dashboard') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 transition">
                    <i class="fas fa-tachometer-alt mr-2 text-gray-400"></i> Mon Dashboard
                </a>
                <a href="{{ route('profile.complete') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 transition">
                    <i class="fas fa-user-circle mr-2 text-gray-400"></i> Mon Profil
                </a>
                @elseif(in_array(auth()->user()->role, ['Admin', 'Administratif', 'Comptable', 'Concierge',
                'Technicien']))
                <a href="/admin"
                    class="block px-3 py-2 rounded-md text-base font-medium text-blue-600 hover:bg-gray-50 transition">
                    <i class="fas fa-shield-alt mr-2 text-blue-400"></i> Panel Administration
                </a>
                @endif

                <form method="POST" action="{{ route('logout') }}" class="w-full pt-2">
                    @csrf
                    <button type="submit"
                        class="w-full text-left block px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50 transition border-t border-gray-100">
                        <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                    </button>
                </form>
                @else
                <button onclick="toggleModal('login-modal')"
                    class="w-full text-left block px-3 py-2 rounded-md text-base font-medium text-blue-600 hover:bg-gray-50">
                    Se connecter
                </button>
                @endauth
            </div>
        </div>
    </nav>

    <main class="pt-16">

        <!-- Flash messages -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            @if(session('success'))
            <div
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded text-center max-w-2xl mx-auto shadow-sm mb-4">
                {{ session('success') }}
            </div>
            @endif
            @if ($errors->any())
            <div
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded text-center max-w-2xl mx-auto shadow-sm mb-4">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        <!-- ═══════════════════════════════ HERO ═══════════════════════════════ -->
        <section
            class="relative hero-gradient py-20 lg:py-32 m-4 sm:mx-6 lg:mx-8 rounded-3xl overflow-hidden shadow-2xl">
            <div
                class="absolute top-0 left-0 w-64 h-64 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob">
            </div>
            <div
                class="absolute top-0 right-0 w-64 h-64 bg-teal-300 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000">
            </div>
            <div
                class="absolute -bottom-8 left-20 w-64 h-64 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-4000">
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="flex flex-col lg:flex-row items-center gap-12">
                    <div class="lg:w-1/2 text-white text-center lg:text-left">
                        <h1
                            class="text-4xl lg:text-5xl xl:text-6xl font-extrabold mb-6 leading-tight tracking-tight drop-shadow-md">
                            Votre futur foyer au cœur du campus.
                        </h1>
                        <p class="text-lg lg:text-xl text-blue-50 mb-8 max-w-lg mx-auto lg:mx-0 drop-shadow">
                            Mokpokpo Université simplifie votre vie étudiante avec un système de gestion de logement
                            moderne, transparent et rapide.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                            <a href="#processus"
                                class="bg-white text-blue-600 px-8 py-4 rounded-xl font-bold text-lg hover:bg-blue-50 hover:scale-105 transition transform shadow-xl text-center">
                                Voir le processus
                            </a>
                            @auth
                            <a href="{{ route('dashboard') }}"
                                class="bg-white text-blue-600 px-8 py-4 rounded-xl font-bold text-lg hover:bg-blue-50 hover:scale-105 transition transform shadow-xl text-center">
                                Voir le dashboard
                            </a>
                            @endauth
                            @guest
                            <button onclick="toggleModal('login-modal')"
                                class="border-2 border-white/80 bg-white/10 backdrop-blur-sm text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-white/20 transition shadow-lg">
                                Se connecter
                            </button>
                            @endguest
                        </div>
                    </div>
                    <div class="lg:w-1/2 relative w-full max-w-md mx-auto">
                        <div
                            class="bg-white/10 p-4 rounded-3xl backdrop-blur-md border border-white/20 shadow-2xl transform rotate-2 hover:rotate-0 transition duration-500">
                            <img src="https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&q=80&w=1000"
                                alt="Résidence Étudiante Mokpokpo"
                                class="rounded-2xl shadow-inner w-full h-auto object-cover aspect-[4/3]">
                        </div>
                        <div class="absolute -bottom-6 -left-6 bg-white p-4 rounded-2xl shadow-xl border border-gray-100 flex items-center gap-4 animate-bounce"
                            style="animation-duration: 3s;">
                            <div class="bg-green-100 p-2 rounded-full text-green-600">
                                <i class="fas fa-check-circle text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-800">100% Digital</p>
                                <p class="text-xs text-gray-500">Processus simplifié</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══════════════════════════════ PROCESSUS ═══════════════════════════════ -->
        <section id="processus" class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span
                    class="inline-block bg-blue-100 text-blue-700 text-sm font-semibold px-4 py-1.5 rounded-full mb-4 tracking-wide uppercase">Guide
                    étudiant</span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900">De la demande à l'installation</h2>
                <p class="mt-4 text-lg text-gray-500 max-w-2xl mx-auto">Chaque étape du processus d'attribution de
                    logement, de votre première demande jusqu'à l'activation de votre logement.</p>
            </div>

            <!-- Steps timeline -->
            <div class="space-y-0">

                <!-- ÉTAPE 1 -->
                <div class="flex flex-col lg:flex-row items-stretch gap-0">
                    <div
                        class="lg:w-1/2 lg:pr-12 pb-8 lg:pb-0 flex flex-col justify-center lg:text-right order-2 lg:order-1">
                        <div
                            class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 hover:shadow-lg transition-shadow duration-300">
                            <div class="flex items-center gap-3 mb-4 lg:justify-end">
                                <span
                                    class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full uppercase tracking-wide">Étape
                                    1</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Obtenir vos identifiants</h3>
                            <p class="text-gray-600 leading-relaxed">
                                L'administration vous remet votre <strong>email étudiant</strong> et un <strong>mot de
                                    passe temporaire</strong> lors de votre inscription à l'université. Ces identifiants
                                vous permettent de vous connecter à la plateforme.
                            </p>
                            <div class="mt-4 flex items-center gap-2 text-sm text-blue-600 font-medium lg:justify-end">
                                <i class="fas fa-envelope"></i>
                                <span>Format : prenom.nom@etudiant.mokpokpo.edu</span>
                            </div>
                        </div>
                    </div>
                    <!-- Center dot -->
                    <div class="hidden lg:flex flex-col items-center order-2">
                        <div
                            class="w-14 h-14 rounded-full bg-blue-600 text-white flex items-center justify-center text-xl font-bold shadow-lg shadow-blue-200 flex-shrink-0 z-10">
                            <i class="fas fa-key"></i>
                        </div>
                        <div class="w-0.5 bg-gradient-to-b from-blue-300 to-indigo-300 flex-1 mt-2"></div>
                    </div>
                    <!-- Mobile icon -->
                    <div class="flex lg:hidden items-center gap-4 mb-4 order-1">
                        <div
                            class="w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center text-lg font-bold flex-shrink-0">
                            <i class="fas fa-key"></i>
                        </div>
                        <span
                            class="text-sm font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full uppercase tracking-wide">Étape
                            1</span>
                    </div>
                    <div class="lg:w-1/2 lg:pl-12 order-3 hidden lg:flex items-center">
                        <div class="bg-blue-50 rounded-2xl p-6 w-full border border-blue-100">
                            <div class="flex items-center gap-3 mb-2">
                                <div
                                    class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs">
                                    <i class="fas fa-user"></i>
                                </div>
                                <span class="font-semibold text-gray-700 text-sm">Votre profil étudiant</span>
                            </div>
                            <div class="space-y-2 text-sm">
                                <div
                                    class="bg-white rounded-lg px-3 py-2 flex items-center gap-2 border border-gray-100">
                                    <i class="fas fa-envelope text-blue-400 text-xs"></i>
                                    <span class="text-gray-600">koffi.mensah@etudiant.mokpokpo.edu</span>
                                </div>
                                <div
                                    class="bg-white rounded-lg px-3 py-2 flex items-center gap-2 border border-gray-100">
                                    <i class="fas fa-lock text-blue-400 text-xs"></i>
                                    <span class="text-gray-400">••••••••••</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ÉTAPE 2 -->
                <div class="flex flex-col lg:flex-row items-stretch gap-0 mt-2 lg:mt-0">
                    <div class="lg:w-1/2 lg:pr-12 hidden lg:flex items-center order-1">
                        <div class="bg-orange-50 rounded-2xl p-6 w-full border border-orange-100">
                            <p class="text-xs font-bold text-orange-600 uppercase tracking-wide mb-3">Formulaire de
                                demande</p>
                            <div class="space-y-2 text-sm">
                                <div
                                    class="flex items-center gap-2 bg-white rounded-lg px-3 py-2 border border-gray-100">
                                    <i class="fas fa-building text-orange-400 text-xs"></i>
                                    <span class="text-gray-600">Bâtiment souhaité</span>
                                    <span class="ml-auto text-gray-400 text-xs">Résidence A</span>
                                </div>
                                <div
                                    class="flex items-center gap-2 bg-white rounded-lg px-3 py-2 border border-gray-100">
                                    <i class="fas fa-bed text-orange-400 text-xs"></i>
                                    <span class="text-gray-600">Type de chambre</span>
                                    <span class="ml-auto text-gray-400 text-xs">Simple</span>
                                </div>
                                <div
                                    class="flex items-center gap-2 bg-white rounded-lg px-3 py-2 border border-gray-100">
                                    <i class="fas fa-star text-orange-400 text-xs"></i>
                                    <span class="text-gray-600">Priorité</span>
                                    <span
                                        class="ml-auto bg-orange-100 text-orange-700 text-xs px-2 py-0.5 rounded-full font-semibold">Normale</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hidden lg:flex flex-col items-center order-2">
                        <div class="w-0.5 bg-gradient-to-b from-blue-300 to-indigo-300 flex-1 mb-2"></div>
                        <div
                            class="w-14 h-14 rounded-full bg-orange-500 text-white flex items-center justify-center text-xl font-bold shadow-lg shadow-orange-200 flex-shrink-0 z-10">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="w-0.5 bg-gradient-to-b from-indigo-300 to-purple-300 flex-1 mt-2"></div>
                    </div>
                    <!-- Mobile icon -->
                    <div class="flex lg:hidden items-center gap-4 mb-4">
                        <div
                            class="w-12 h-12 rounded-full bg-orange-500 text-white flex items-center justify-center text-lg">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <span
                            class="text-sm font-bold text-orange-600 bg-orange-50 px-3 py-1 rounded-full uppercase tracking-wide">Étape
                            2</span>
                    </div>
                    <div class="lg:w-1/2 lg:pl-12 order-3 flex flex-col justify-center">
                        <div
                            class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 hover:shadow-lg transition-shadow duration-300">
                            <div class="flex items-center gap-3 mb-4">
                                <span
                                    class="text-xs font-bold text-orange-600 bg-orange-50 px-3 py-1 rounded-full uppercase tracking-wide">Étape
                                    2</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Soumettre une demande de logement</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Une fois connecté, complétez votre profil étudiant et soumettez votre demande en
                                choisissant le <strong>bâtiment</strong> et le <strong>type de chambre</strong>
                                souhaités. Votre demande est enregistrée avec une priorité calculée automatiquement
                                (handicap, ancienneté, etc.).
                            </p>
                            <div class="mt-4 flex flex-wrap gap-2">
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">Profil complet
                                    requis</span>
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">Priorité
                                    automatique</span>
                                <span class="text-xs bg-orange-100 text-orange-700 px-2 py-1 rounded-full">~5
                                    minutes</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ÉTAPE 3 -->
                <div class="flex flex-col lg:flex-row items-stretch gap-0 mt-2 lg:mt-0">
                    <div
                        class="lg:w-1/2 lg:pr-12 flex flex-col justify-center lg:text-right order-2 lg:order-1 mt-4 lg:mt-0">
                        <div
                            class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 hover:shadow-lg transition-shadow duration-300">
                            <div class="flex items-center gap-3 mb-4 lg:justify-end">
                                <span
                                    class="text-xs font-bold text-purple-600 bg-purple-50 px-3 py-1 rounded-full uppercase tracking-wide">Étape
                                    3</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Traitement par l'administration</h3>
                            <p class="text-gray-600 leading-relaxed">
                                L'équipe administrative étudie votre dossier. Si une chambre correspond à votre demande,
                                ils vous <strong>proposent un logement spécifique</strong> et changent le statut de
                                votre demande en <strong>«&nbsp;Acceptée&nbsp;»</strong>. Vous êtes notifié depuis votre
                                tableau de bord.
                            </p>
                            <div class="mt-4 flex flex-wrap gap-2 lg:justify-end">
                                <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full">⏳ En
                                    attente</span>
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">✅
                                    Acceptée</span>
                                <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded-full">❌ Refusée</span>
                            </div>
                        </div>
                    </div>
                    <div class="hidden lg:flex flex-col items-center order-2">
                        <div class="w-0.5 bg-gradient-to-b from-orange-300 to-purple-300 flex-1 mb-2"></div>
                        <div
                            class="w-14 h-14 rounded-full bg-purple-600 text-white flex items-center justify-center text-xl font-bold shadow-lg shadow-purple-200 flex-shrink-0 z-10">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="w-0.5 bg-gradient-to-b from-purple-300 to-teal-300 flex-1 mt-2"></div>
                    </div>
                    <!-- Mobile icon -->
                    <div class="flex lg:hidden items-center gap-4 mb-4 order-1">
                        <div
                            class="w-12 h-12 rounded-full bg-purple-600 text-white flex items-center justify-center text-lg">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <span
                            class="text-sm font-bold text-purple-600 bg-purple-50 px-3 py-1 rounded-full uppercase tracking-wide">Étape
                            3</span>
                    </div>
                    <div class="lg:w-1/2 lg:pl-12 order-3 hidden lg:flex items-center">
                        <div class="bg-purple-50 rounded-2xl p-6 w-full border border-purple-100">
                            <p class="text-xs font-bold text-purple-600 uppercase tracking-wide mb-3">Décision
                                administrative</p>
                            <div class="space-y-3">
                                <div class="flex items-start gap-3">
                                    <div
                                        class="w-6 h-6 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xs flex-shrink-0 mt-0.5">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700">Chambre 214 - Bât. A proposée</p>
                                        <p class="text-xs text-gray-500">Chambre simple • 45 000 FCFA/mois</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div
                                        class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs flex-shrink-0 mt-0.5">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700">Délai de réponse : 5 jours ouvrés
                                        </p>
                                        <p class="text-xs text-gray-500">Selon disponibilités</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ÉTAPE 4 -->
                <div class="flex flex-col lg:flex-row items-stretch gap-0 mt-2 lg:mt-0">
                    <div class="lg:w-1/2 lg:pr-12 hidden lg:flex items-center order-1">
                        <div class="bg-teal-50 rounded-2xl p-6 w-full border border-teal-100">
                            <p class="text-xs font-bold text-teal-600 uppercase tracking-wide mb-3">Contrat d'habitation
                            </p>
                            <div class="space-y-2">
                                <div
                                    class="flex items-center justify-between bg-white rounded-lg px-3 py-2 border border-gray-100 text-sm">
                                    <span class="text-gray-600 flex items-center gap-2"><i
                                            class="fas fa-signature text-teal-400 text-xs"></i> Signature
                                        étudiant</span>
                                    <span
                                        class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full font-semibold">✓
                                        Signé</span>
                                </div>
                                <div
                                    class="flex items-center justify-between bg-white rounded-lg px-3 py-2 border border-gray-100 text-sm">
                                    <span class="text-gray-600 flex items-center gap-2"><i
                                            class="fas fa-signature text-teal-400 text-xs"></i> Signature admin</span>
                                    <span
                                        class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full font-semibold">✓
                                        Signé</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hidden lg:flex flex-col items-center order-2">
                        <div class="w-0.5 bg-gradient-to-b from-purple-300 to-teal-300 flex-1 mb-2"></div>
                        <div
                            class="w-14 h-14 rounded-full bg-teal-600 text-white flex items-center justify-center text-xl font-bold shadow-lg shadow-teal-200 flex-shrink-0 z-10">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <div class="w-0.5 bg-gradient-to-b from-teal-300 to-yellow-300 flex-1 mt-2"></div>
                    </div>
                    <!-- Mobile icon -->
                    <div class="flex lg:hidden items-center gap-4 mb-4">
                        <div
                            class="w-12 h-12 rounded-full bg-teal-600 text-white flex items-center justify-center text-lg">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <span
                            class="text-sm font-bold text-teal-600 bg-teal-50 px-3 py-1 rounded-full uppercase tracking-wide">Étape
                            4</span>
                    </div>
                    <div class="lg:w-1/2 lg:pl-12 order-3 flex flex-col justify-center mt-4 lg:mt-0">
                        <div
                            class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 hover:shadow-lg transition-shadow duration-300">
                            <div class="flex items-center gap-3 mb-4">
                                <span
                                    class="text-xs font-bold text-teal-600 bg-teal-50 px-3 py-1 rounded-full uppercase tracking-wide">Étape
                                    4</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Signature du contrat d'habitation</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Un <strong>contrat d'habitation</strong> est généré pour la chambre attribuée.
                                L'étudiant et l'administratif le signent numériquement depuis la plateforme. Les deux
                                signatures sont requises pour passer à l'étape suivante.
                            </p>
                            <div class="mt-4 p-3 bg-teal-50 rounded-xl border border-teal-100 text-sm text-teal-700">
                                <i class="fas fa-info-circle mr-1"></i> Le contrat détaille la durée, le montant mensuel
                                et les règles de la résidence.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ÉTAPE 5 -->
                <div class="flex flex-col lg:flex-row items-stretch gap-0 mt-2 lg:mt-0">
                    <div
                        class="lg:w-1/2 lg:pr-12 flex flex-col justify-center lg:text-right order-2 lg:order-1 mt-4 lg:mt-0">
                        <div
                            class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 hover:shadow-lg transition-shadow duration-300">
                            <div class="flex items-center gap-3 mb-4 lg:justify-end">
                                <span
                                    class="text-xs font-bold text-yellow-600 bg-yellow-50 px-3 py-1 rounded-full uppercase tracking-wide">Étape
                                    5</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">État des lieux d'entrée & premier paiement
                            </h3>
                            <p class="text-gray-600 leading-relaxed">
                                Le concierge réalise un <strong>état des lieux d'entrée</strong> avec vous. Vous validez
                                ensemble l'état de la chambre. En parallèle, vous effectuez votre <strong>premier
                                    versement</strong> (équivalent à 3 mois de loyer) auprès du service comptable.
                            </p>
                            <div class="mt-4 grid grid-cols-2 gap-2 text-sm text-left">
                                <div class="bg-yellow-50 rounded-lg p-3 border border-yellow-100">
                                    <i class="fas fa-clipboard-check text-yellow-600 mb-1 block"></i>
                                    <p class="font-semibold text-gray-700 text-xs">État des lieux</p>
                                    <p class="text-gray-500 text-xs">Signé par les 2 parties</p>
                                </div>
                                <div class="bg-yellow-50 rounded-lg p-3 border border-yellow-100">
                                    <i class="fas fa-coins text-yellow-600 mb-1 block"></i>
                                    <p class="font-semibold text-gray-700 text-xs">Premier versement</p>
                                    <p class="text-gray-500 text-xs">3 mois de caution</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hidden lg:flex flex-col items-center order-2">
                        <div class="w-0.5 bg-gradient-to-b from-teal-300 to-yellow-300 flex-1 mb-2"></div>
                        <div
                            class="w-14 h-14 rounded-full bg-yellow-500 text-white flex items-center justify-center text-xl font-bold shadow-lg shadow-yellow-200 flex-shrink-0 z-10">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div class="w-0.5 bg-gradient-to-b from-yellow-300 to-green-400 flex-1 mt-2"></div>
                    </div>
                    <!-- Mobile icon -->
                    <div class="flex lg:hidden items-center gap-4 mb-4 order-1">
                        <div
                            class="w-12 h-12 rounded-full bg-yellow-500 text-white flex items-center justify-center text-lg">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <span
                            class="text-sm font-bold text-yellow-600 bg-yellow-50 px-3 py-1 rounded-full uppercase tracking-wide">Étape
                            5</span>
                    </div>
                    <div class="lg:w-1/2 lg:pl-12 order-3 hidden lg:flex items-center">
                        <div class="bg-yellow-50 rounded-2xl p-6 w-full border border-yellow-100">
                            <p class="text-xs font-bold text-yellow-600 uppercase tracking-wide mb-3">Conditions
                                d'activation</p>
                            <div class="space-y-2">
                                <div
                                    class="flex items-center gap-2 bg-white rounded-lg px-3 py-2.5 border border-gray-100 text-sm">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                    <span class="text-gray-700">Contrat signé (2/2)</span>
                                </div>
                                <div
                                    class="flex items-center gap-2 bg-white rounded-lg px-3 py-2.5 border border-gray-100 text-sm">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                    <span class="text-gray-700">État des lieux signé</span>
                                </div>
                                <div
                                    class="flex items-center gap-2 bg-white rounded-lg px-3 py-2.5 border border-gray-100 text-sm">
                                    <i class="fas fa-spinner text-yellow-500"></i>
                                    <span class="text-gray-700">3 mois payés (en cours…)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ÉTAPE 6 — ACTIVATION -->
                <div class="flex flex-col lg:flex-row items-stretch gap-0 mt-2 lg:mt-0">
                    <div class="lg:w-1/2 lg:pr-12 hidden lg:flex items-center order-1">
                        <div
                            class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 w-full border border-green-200">
                            <p class="text-xs font-bold text-green-600 uppercase tracking-wide mb-3">🏠 Logement activé
                                !</p>
                            <div class="text-center py-4">
                                <div
                                    class="w-16 h-16 rounded-2xl bg-green-500 text-white flex items-center justify-center text-2xl mx-auto mb-3 shadow-lg shadow-green-200">
                                    <i class="fas fa-home"></i>
                                </div>
                                <p class="font-bold text-gray-800">Chambre 214 — Bât. A</p>
                                <p class="text-sm text-gray-500 mt-1">Statut : <span
                                        class="text-green-600 font-semibold">Actif</span></p>
                                <div class="mt-3 bg-green-100 rounded-lg px-4 py-2 text-xs text-green-700 font-medium">
                                    Félicitations ! Vous êtes chez vous. 🎉
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hidden lg:flex flex-col items-center order-2">
                        <div class="w-0.5 bg-gradient-to-b from-yellow-300 to-green-400 flex-1 mb-2"></div>
                        <div
                            class="w-14 h-14 rounded-full bg-green-500 text-white flex items-center justify-center text-xl font-bold shadow-lg shadow-green-200 flex-shrink-0 z-10">
                            <i class="fas fa-home"></i>
                        </div>
                    </div>
                    <!-- Mobile icon -->
                    <div class="flex lg:hidden items-center gap-4 mb-4">
                        <div
                            class="w-12 h-12 rounded-full bg-green-500 text-white flex items-center justify-center text-lg">
                            <i class="fas fa-home"></i>
                        </div>
                        <span
                            class="text-sm font-bold text-green-600 bg-green-50 px-3 py-1 rounded-full uppercase tracking-wide">Étape
                            6</span>
                    </div>
                    <div class="lg:w-1/2 lg:pl-12 order-3 flex flex-col justify-center mt-4 lg:mt-0">
                        <div
                            class="bg-white rounded-2xl shadow-sm border border-green-200 p-8 hover:shadow-lg transition-shadow duration-300 relative overflow-hidden">
                            <div
                                class="absolute top-0 right-0 w-24 h-24 bg-green-50 rounded-full -translate-y-8 translate-x-8">
                            </div>
                            <div class="flex items-center gap-3 mb-4">
                                <span
                                    class="text-xs font-bold text-green-600 bg-green-50 px-3 py-1 rounded-full uppercase tracking-wide">Étape
                                    6 — Finale</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Activation du logement 🎉</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Lorsque les <strong>3 conditions</strong> sont remplies (contrat signé, état des lieux
                                signé, paiements à jour), le logement est <strong>automatiquement activé</strong> par le
                                système. Le statut de votre chambre passe à <strong>«&nbsp;Actif&nbsp;»</strong> et vous
                                pouvez y emménager immédiatement.
                            </p>
                            <div
                                class="mt-4 p-3 bg-green-50 rounded-xl border border-green-100 text-sm text-green-700 font-medium">
                                <i class="fas fa-bolt mr-1"></i> L'activation est automatique — aucune démarche
                                supplémentaire nécessaire.
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- /steps -->

            <!-- CTA après le processus -->
            <div class="mt-16 text-center">
                <div
                    class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-3xl p-10 shadow-2xl text-white max-w-3xl mx-auto">
                    <h3 class="text-2xl font-bold mb-3">Prêt à démarrer ?</h3>
                    <p class="text-blue-100 mb-6">Connectez-vous avec vos identifiants étudiants pour accéder à votre
                        tableau de bord et commencer votre demande.</p>
                    @guest
                    <button onclick="toggleModal('login-modal')"
                        class="bg-white text-blue-600 px-10 py-4 rounded-xl font-bold text-lg hover:bg-blue-50 hover:scale-105 transition transform shadow-xl">
                        <i class="fas fa-sign-in-alt mr-2"></i> Se connecter
                    </button>
                    @else
                    <a href="{{ route('dashboard') }}"
                        class="inline-block bg-white text-blue-600 px-10 py-4 rounded-xl font-bold text-lg hover:bg-blue-50 hover:scale-105 transition transform shadow-xl">
                        <i class="fas fa-tachometer-alt mr-2"></i> Mon tableau de bord
                    </a>
                    @endguest
                </div>
            </div>
        </section>

        <!-- ═══════════════════════════════ FEATURES ═══════════════════════════════ -->
        <section id="residences" class="py-16 bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900">Pourquoi choisir nos résidences ?</h2>
                    <p class="mt-4 text-lg text-gray-500">Des services pensés pour votre réussite académique.</p>
                </div>
                <div class="grid md:grid-cols-3 gap-8">
                    <div
                        class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 text-center hover:shadow-md transition">
                        <div
                            class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 text-2xl shadow-inner">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-gray-900">Processus Rapide</h3>
                        <p class="text-gray-600">De la demande à l'installation, tout se fait en ligne. Plus de files
                            d'attente, plus de paperasse inutile.</p>
                    </div>
                    <div
                        class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 text-center hover:shadow-md transition">
                        <div
                            class="w-16 h-16 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center mx-auto mb-6 text-2xl shadow-inner">
                            <i class="fas fa-file-contract"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-gray-900">Contrat Numérique</h3>
                        <p class="text-gray-600">Fini la paperasse. Signez votre contrat et votre état des lieux
                            directement depuis votre smartphone.</p>
                    </div>
                    <div
                        class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 text-center hover:shadow-md transition">
                        <div
                            class="w-16 h-16 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-6 text-2xl shadow-inner">
                            <i class="fas fa-tools"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-gray-900">Maintenance Réactive</h3>
                        <p class="text-gray-600">Un souci technique ? Signalez-le en un clic sur la plateforme et suivez
                            l'intervention en temps réel.</p>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-2">
                    <i class="fas fa-university text-xl text-gray-500"></i>
                    <span class="text-xl font-bold text-gray-300">Mokpokpo</span>
                </div>
                <div class="text-sm">&copy; 2026 Mokpokpo Université — Service des Logements. Tous droits réservés.
                </div>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </footer>

    @guest
    <!-- Login Modal only (register supprimé) -->
    <div id="login-modal"
        class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
        <div
            class="bg-white w-full max-w-md rounded-3xl p-8 shadow-2xl relative transform transition-all duration-200 scale-95 opacity-0">
            <button onclick="toggleModal('login-modal')"
                class="absolute top-6 right-6 text-gray-400 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center transition">
                <i class="fas fa-times"></i>
            </button>
            <div class="text-center mb-8">
                <div
                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 text-blue-600 mb-4">
                    <i class="fas fa-user text-xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Bon retour !</h2>
                <p class="text-sm text-gray-500 mt-1">Accédez à votre espace étudiant Mokpokpo</p>
            </div>
            <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email étudiant</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}"
                            placeholder="prenom.nom@etudiant.mokpokpo.edu"
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-gray-50 focus:bg-white"
                            required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Mot de passe</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input type="password" name="password" placeholder="••••••••"
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-gray-50 focus:bg-white"
                            required>
                    </div>
                </div>
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-3.5 rounded-xl font-bold hover:bg-blue-700 focus:ring-4 focus:ring-blue-100 transition shadow-lg mt-6">
                    Se connecter
                </button>
            </form>
        </div>
    </div>
    @endguest

    <script>
        function toggleModal(id) {
            const modal = document.getElementById(id);
            if (!modal) return;
            const inner = modal.children[0];
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    inner.classList.remove('scale-95', 'opacity-0');
                    inner.classList.add('scale-100', 'opacity-100');
                }, 10);
            } else {
                inner.classList.add('scale-95', 'opacity-0');
                inner.classList.remove('scale-100', 'opacity-100');
                setTimeout(() => modal.classList.add('hidden'), 200);
            }
        }

        window.onclick = function (e) {
            if (e.target.classList.contains('bg-gray-900/60')) {
                document.querySelectorAll('.fixed.inset-0:not(.hidden)').forEach(m => toggleModal(m.id));
            }
        };

        @if ($errors -> any())
            document.addEventListener('DOMContentLoaded', () => toggleModal('login-modal'));
        @endif
    </script>

</body>

</html>