<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mokpokpo Université - Logements Étudiants</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/dist/css/all.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .glass-morphism {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .hero-gradient {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 glass-morphism shadow-sm" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    <div class="bg-blue-600 p-2 rounded-lg text-white">
                        <i class="fas fa-university text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold text-blue-900 tracking-tight">Mokpokpo</span>
                </div>
                <div class="hidden md:flex space-x-8 text-gray-600 font-medium">
                    <a href="#" class="hover:text-blue-600 transition">Accueil</a>
                    <a href="#" class="hover:text-blue-600 transition">Résidences</a>
                    <a href="#" class="hover:text-blue-600 transition">Aide</a>
                </div>
                <div class="hidden md:flex">
                    @auth
                        <div class="flex items-center gap-4">
                            <span class="text-gray-700 font-medium">{{ auth()->user()->email }} ({{ auth()->user()->role }})</span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="bg-red-50 text-red-600 px-6 py-2 rounded-full font-semibold hover:bg-red-100 transition">
                                    Déconnexion
                                </button>
                            </form>
                            @if(in_array(auth()->user()->role, ['Admin', 'Administratif', 'Comptable', 'Concierge', 'Technicien']))
                                <a href="/admin" class="bg-blue-600 text-white px-6 py-2 rounded-full font-semibold hover:bg-blue-700 transition shadow-lg">Panel</a>
                            @endif
                        </div>
                    @else
                        <button onclick="toggleModal('login-modal')" class="bg-blue-600 text-white px-6 py-2 rounded-full font-semibold hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                            Se connecter
                        </button>
                    @endauth
                </div>
                <div class="-mr-2 flex items-center md:hidden">
                    <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500" aria-controls="mobile-menu" aria-expanded="false">
                      <span class="sr-only">Open main menu</span>
                      <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                      </svg>
                      <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu, show/hide based on menu state. -->
        <div class="md:hidden" id="mobile-menu" x-show="mobileMenuOpen" x-collapse>
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
              <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Accueil</a>
              <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Résidences</a>
              <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Aide</a>
            </div>
            <div class="pt-4 pb-3 border-t border-gray-200">
                <div class="px-2 space-y-1">
                    @auth
                        <div class="block px-3 py-2 rounded-md text-base font-medium text-gray-700">{{ auth()->user()->email }} ({{ auth()->user()->role }})</div>
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full text-left block px-3 py-2 rounded-md text-base font-medium text-red-600 hover:text-red-800 hover:bg-gray-50">
                                Déconnexion
                            </button>
                        </form>
                        @if(in_array(auth()->user()->role, ['Admin', 'Administratif', 'Comptable', 'Concierge', 'Technicien']))
                            <a href="/admin" class="block px-3 py-2 rounded-md text-base font-medium text-blue-600 hover:text-blue-800 hover:bg-gray-50">Panel Admin</a>
                        @endif
                    @else
                        <button onclick="toggleModal('login-modal'); document.getElementById('mobile-menu').style.display='none'" class="w-full text-left block px-3 py-2 rounded-md text-base font-medium text-blue-600 hover:text-blue-800 hover:bg-gray-50">
                            Se connecter
                        </button>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="pt-16 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative text-center mx-auto max-w-2xl shadow-sm mb-4" role="alert">
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-center mx-auto max-w-2xl shadow-sm mb-4" role="alert">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <section class="relative hero-gradient py-20 lg:py-32 m-4 sm:mx-6 lg:mx-8 rounded-3xl overflow-hidden shadow-2xl">
            <!-- Decorative blobs -->
            <div class="absolute top-0 left-0 w-64 h-64 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-teal-300 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-20 w-64 h-64 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-4000"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="flex flex-col lg:flex-row items-center gap-12">
                    <div class="lg:w-1/2 text-white text-center lg:text-left">
                        <h1 class="text-4xl lg:text-5xl xl:text-6xl font-extrabold mb-6 leading-tight tracking-tight drop-shadow-md">
                            Votre futur foyer au cœur du campus.
                        </h1>
                        <p class="text-lg lg:text-xl text-blue-50 mb-8 max-w-lg mx-auto lg:mx-0 drop-shadow">
                            Mokpokpo Université simplifie votre vie étudiante avec un système de gestion de logement moderne, transparent et rapide.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                            @guest
                            <button onclick="toggleModal('register-modal')" class="bg-white text-blue-600 px-8 py-4 rounded-xl font-bold text-lg hover:bg-blue-50 hover:scale-105 transition transform shadow-xl">
                                Créer mon compte
                            </button>
                            @endguest
                            <button class="border-2 border-white/80 bg-white/10 backdrop-blur-sm text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-white/20 transition shadow-lg">
                                Voir les résidences
                            </button>
                        </div>
                    </div>
                    <div class="lg:w-1/2 relative w-full max-w-md mx-auto">
                        <div class="bg-white/10 p-4 rounded-3xl backdrop-blur-md border border-white/20 shadow-2xl transform rotate-2 hover:rotate-0 transition duration-500">
                            <img src="https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&q=80&w=1000" alt="Résidence Étudiante Mokpokpo" class="rounded-2xl shadow-inner w-full h-auto object-cover aspect-[4/3]">
                        </div>
                        
                        <!-- Floating badge -->
                        <div class="absolute -bottom-6 -left-6 bg-white p-4 rounded-2xl shadow-xl border border-gray-100 flex items-center gap-4 animate-bounce" style="animation-duration: 3s;">
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

        <!-- Features -->
        <section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">Pourquoi choisir nos résidences ?</h2>
                <p class="mt-4 text-lg text-gray-500">Des services pensés pour votre réussite académique.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 text-center hover:shadow-md transition">
                    <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 text-2xl shadow-inner">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Demande Rapide</h3>
                    <p class="text-gray-600">Complétez votre profil et soumettez votre demande de logement en moins de 5 minutes chrono.</p>
                </div>
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 text-center hover:shadow-md transition">
                    <div class="w-16 h-16 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center mx-auto mb-6 text-2xl shadow-inner">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Contrat Numérique</h3>
                    <p class="text-gray-600">Fini la paperasse. Signez votre contrat et votre état des lieux directement depuis votre smartphone.</p>
                </div>
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 text-center hover:shadow-md transition">
                    <div class="w-16 h-16 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-6 text-2xl shadow-inner">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Maintenance Réactive</h3>
                    <p class="text-gray-600">Un souci technique ? Signalez-le en un clic sur la plateforme et suivez l'intervention en temps réel.</p>
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
                <div class="text-sm">
                    &copy; 2026 Mokpokpo Université - Service des Logements. Tous droits réservés.
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
    <!-- Auth Modals using Alpine.js for simpler state management if needed, but keeping vanilla JS for compatibility with user request -->
    <!-- Login Modal -->
    <div id="login-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm transition-opacity">
        <div class="bg-white w-full max-w-md rounded-3xl p-8 shadow-2xl relative transform transition-all">
            <button onclick="toggleModal('login-modal')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center transition">
                <i class="fas fa-times"></i>
            </button>
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 text-blue-600 mb-4">
                    <i class="fas fa-user text-xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Bon retour !</h2>
                <p class="text-sm text-gray-500 mt-1">Accédez à votre espace étudiant Mokpokpo</p>
            </div>
            
            <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="nom@etudiant.univ.tg" class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-gray-50 focus:bg-white" required>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-sm font-semibold text-gray-700">Mot de passe</label>
                        <a href="#" class="text-sm font-medium text-blue-600 hover:underline">Oublié ?</a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input type="password" name="password" placeholder="••••••••" class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-gray-50 focus:bg-white" required>
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-blue-600 text-white py-3.5 rounded-xl font-bold hover:bg-blue-700 focus:ring-4 focus:ring-blue-100 transition shadow-lg mt-6">
                    Se connecter
                </button>
            </form>
            
            <div class="mt-6 text-center text-sm text-gray-600">
                Pas encore de compte ? 
                <button onclick="switchModal('login-modal', 'register-modal')" class="text-blue-600 font-bold hover:underline">Créer un compte</button>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div id="register-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm transition-opacity">
        <div class="bg-white w-full max-w-md rounded-3xl p-8 shadow-2xl relative max-h-[90vh] overflow-y-auto custom-scrollbar">
            <button onclick="toggleModal('register-modal')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center transition z-10">
                <i class="fas fa-times"></i>
            </button>
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-green-100 text-green-600 mb-4">
                    <i class="fas fa-user-plus text-xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Inscription</h2>
                <p class="text-sm text-gray-500 mt-1">Commencez votre demande de logement</p>
            </div>
            
            <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Étudiant</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="nom@etudiant.univ.tg" class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-gray-50 focus:bg-white" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Mot de passe</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input type="password" name="password" placeholder="Min. 8 caractères" class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-gray-50 focus:bg-white" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirmer le mot de passe</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <input type="password" name="password_confirmation" placeholder="••••••••" class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-gray-50 focus:bg-white" required>
                    </div>
                </div>
                
                <div class="flex items-start gap-3 pt-2">
                    <div class="flex items-center h-5">
                        <input type="checkbox" id="terms" name="terms" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" required>
                    </div>
                    <label for="terms" class="text-xs text-gray-600 leading-tight">
                        J'ai lu et j'accepte les <a href="#" class="text-blue-600 hover:underline">conditions d'utilisation</a> et la <a href="#" class="text-blue-600 hover:underline">politique de confidentialité</a> de l'université.
                    </label>
                </div>
                
                <button type="submit" class="w-full bg-blue-600 text-white py-3.5 rounded-xl font-bold hover:bg-blue-700 focus:ring-4 focus:ring-blue-100 transition shadow-lg mt-4">
                    Créer mon compte
                </button>
            </form>
            
            <div class="mt-6 text-center text-sm text-gray-600">
                Déjà inscrit ? 
                <button onclick="switchModal('register-modal', 'login-modal')" class="text-blue-600 font-bold hover:underline">Se connecter</button>
            </div>
        </div>
    </div>
    @endguest

    <script>
        function toggleModal(id) {
            const modal = document.getElementById(id);
            if(modal) {
                if (modal.classList.contains('hidden')) {
                    modal.classList.remove('hidden');
                    // Small delay to allow display:block to apply before animating opacity
                    setTimeout(() => {
                        modal.children[0].classList.add('scale-100', 'opacity-100');
                        modal.children[0].classList.remove('scale-95', 'opacity-0');
                    }, 10);
                } else {
                    modal.children[0].classList.add('scale-95', 'opacity-0');
                    modal.children[0].classList.remove('scale-100', 'opacity-100');
                    setTimeout(() => {
                        modal.classList.add('hidden');
                    }, 200); // Wait for transition
                }
            }
        }

        function switchModal(closeId, openId) {
            const close = document.getElementById(closeId);
            const open = document.getElementById(openId);
            
            if(close) {
                 close.classList.add('hidden');
            }
            if(open) {
                 open.classList.remove('hidden');
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('bg-gray-900/60')) {
                // Find visible modals and toggle them
                const modals = document.querySelectorAll('.fixed.inset-0:not(.hidden)');
                modals.forEach(modal => toggleModal(modal.id));
            }
        }

        // Add CSS animations classes dynamically required by script
        document.addEventListener('DOMContentLoaded', () => {
            const modals = document.querySelectorAll('.fixed.inset-0 > div');
            modals.forEach(modal => {
                modal.classList.add('transform', 'transition-all', 'duration-200', 'scale-95', 'opacity-0');
            });
        });

        // Open modal if there are errors (from Laravel validation context)
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', () => {
                @if(old('password_confirmation') || $errors->has('terms'))
                    toggleModal('register-modal');
                @else
                    toggleModal('login-modal');
                @endif
            });
        @endif
    </script>
    
    <style>
        /* Custom styles for animations that Tailwind might lack or generic tweaks */
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
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
        
        /* Custom Scrollbar for modals if needed on smaller screens */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1; 
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1; 
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8; 
        }
    </style>
</body>
</html>