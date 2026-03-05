@extends('layouts.student')

@section('title', 'Nos Résidences - Mokpokpo')

@section('styles')
<style>
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
</style>
@endsection

@section('content')
<!-- Header Section -->
<header class="bg-blue-600 py-12 px-6">
    <div class="max-w-7xl mx-auto text-white">
        <h1 class="text-4xl font-extrabold mb-4 tracking-tight">Disponibilité des Résidences</h1>
        <p class="text-blue-100 text-lg opacity-90 max-w-2xl">Visualisez en temps réel l'occupation de nos
            différents bâtiments pour mieux orienter votre demande de logement.</p>
    </div>
</header>

<!-- Main Content -->
<div class="max-w-7xl w-full mx-auto px-6 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
        @foreach($stats as $batiment)
        <div
            class="stat-card bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300">
            <!-- Batiment Info -->
            <div class="p-8 border-b border-gray-50 bg-gradient-to-br from-white to-gray-50">
                <div class="flex items-start justify-between mb-4">
                    <div class="bg-blue-100 p-3 rounded-2xl text-blue-600">
                        <i class="fas fa-building text-2xl"></i>
                    </div>
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">
                        {{ $batiment['disponible_total'] }} Dispos
                    </span>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-1">{{ $batiment['nom'] }}</h2>
                <p class="text-sm text-gray-500 mb-6"><i class="fas fa-map-marker-alt mr-1"></i> {{
                    $batiment['adresse'] }}</p>

                <!-- Global Progress Bar -->
                <div>
                    <div class="flex justify-between text-xs font-bold text-gray-400 mb-2 uppercase tracking-wide">
                        <span>Taux d'occupation</span>
                        <span>{{ $batiment['total_logements'] > 0 ? round(($batiment['occupe_total'] /
                            $batiment['total_logements']) * 100) : 0 }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3">
                        <div class="bg-blue-600 h-3 rounded-full transition-all duration-500"
                            style="width: {{ $batiment['total_logements'] > 0 ? ($batiment['occupe_total'] / $batiment['total_logements']) * 100 : 0 }}%">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Types BreakDown -->
            <div class="p-8 space-y-6">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest">Par type de logement</h3>

                @if(count($batiment['types']) > 0)
                <div class="space-y-4">
                    @foreach($batiment['types'] as $type)
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <div class="flex justify-between items-center mb-3">
                            <span class="font-bold text-gray-800">{{ $type['nom'] }}</span>
                            <span class="text-sm font-semibold text-blue-600">{{ number_format($type['prix'], 0,
                                ',', ' ') }} F/mois</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="text-center">
                                <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Total</p>
                                <p class="text-lg font-bold text-gray-900">{{ $type['total'] }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Libres</p>
                                <p class="text-lg font-bold text-green-600">{{ $type['disponible'] }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Occupés</p>
                                <p class="text-lg font-bold text-gray-400">{{ $type['occupe'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="py-8 text-center text-gray-400">
                    <i class="fas fa-bed text-3xl mb-2 opacity-20"></i>
                    <p class="text-xs">Aucun logement configuré</p>
                </div>
                @endif
            </div>

            <!-- Footer Card -->
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-100 flex justify-center">
                <a href="{{ route('demandes.create', ['batiment' => $batiment['id']]) }}"
                    class="text-blue-600 font-bold text-sm hover:underline flex items-center gap-2">
                    Faire une demande ici <i class="fas fa-chevron-right text-xs"></i>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection