<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mokpokpo')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/dist/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
    @yield('styles')
</head>

<body class="bg-gray-50 flex flex-col min-h-screen">
    @php
    $user = auth()->user();
    @endphp
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group hover:opacity-90 transition">
                    <div class="bg-blue-600 p-2 rounded-lg text-white">
                        <i class="fas fa-university text-xl"></i>
                    </div>
                    <span class="text-xl font-bold text-gray-900 tracking-tight">Mokpokpo</span>
                </a>
                <div class="hidden md:flex ml-10 space-x-8">
                    <a href="{{ route('dashboard') }}"
                        class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 {{ Route::is('dashboard') ? 'border-blue-500 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm">
                        Tableau de bord
                    </a>
                    <a href="{{ route('residences.index') }}"
                        class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 {{ Route::is('residences.*') ? 'border-blue-500 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm">
                        Nos Résidences
                    </a>
                    <a href="{{ route('home') }}"
                        class="text-gray-500 hover:text-blue-600 transition text-sm flex items-center">Comment ça
                        marche</a>
                </div>
                <div>
                    <div class="flex items-center gap-4">
                        <span class="text-gray-700 font-medium hidden sm:inline">{{ $user->email }}</span>
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

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-gray-200 py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p class="text-sm text-gray-500">&copy; 2026 Mokpokpo Université. Toutes les données sont mises à jour en
                temps réel.</p>
        </div>
    </footer>
    @yield('scripts')
</body>

</html>