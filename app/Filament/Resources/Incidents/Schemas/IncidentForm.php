<?php

namespace App\Filament\Resources\Incidents\Schemas;

use App\Models\Etudiant;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class IncidentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

            // --- Étudiant concerné (recherche par nom + prénom) ---
            Select::make('etudiant_id')
            ->label('Étudiant concerné')
            ->required()
            ->searchable()
            ->getSearchResultsUsing(function (string $search) {
            return Etudiant::where('nom', 'like', "%{$search}%")
                ->orWhere('prenom', 'like', "%{$search}%")
                ->limit(20)
                ->get()
                ->mapWithKeys(fn($e) => [
            $e->id => "{$e->nom} {$e->prenom}"
            ]);
        })
            ->getOptionLabelUsing(fn($value) => optional(Etudiant::find($value))->nom . ' ' . optional(Etudiant::find($value))->prenom)
            ->columnSpanFull(),

            // --- Logement (Optionnel) ---
            Select::make('logement_id')
            ->label('Logement concerné (optionnel)')
            ->relationship('logement', 'numero_chambre')
            ->searchable()
            ->nullable(),

            // --- Type d'incident ---
            Select::make('type')
            ->label("Type d'incident")
            ->required()
            ->options([
                'Comportement' => 'Comportement',
                'Dégradation' => 'Dégradation',
                'Voisinage' => 'Voisinage',
                'Violence' => 'Violence',
                'Autre' => 'Autre',
            ]),

            // --- Gravité 0 à 10 ---
            TextInput::make('gravite')
            ->label('Gravité (0 = mineur, 10 = très grave)')
            ->required()
            ->numeric()
            ->minValue(0)
            ->maxValue(10)
            ->default(0)
            ->step(1),

            // --- Description ---
            Textarea::make('description')
            ->label('Description de l\'incident')
            ->required()
            ->rows(4)
            ->columnSpanFull(),

            // --- Statut ---
            Select::make('statut')
            ->label('Statut')
            ->required()
            ->options([
                'Nouveau' => 'Nouveau',
                'En cours' => 'En cours',
                'Clôturé' => 'Clôturé',
            ])
            ->default('Nouveau'),

            // --- Rapporté par (auto-rempli avec l'utilisateur connecté) ---
            Select::make('rapporte_par_id')
            ->label('Rapporté par')
            ->options(
            User::whereIn('role', ['Admin', 'Concierge', 'Administratif'])
            ->get()
            ->mapWithKeys(fn($u) => [$u->id => "{$u->name} ({$u->role})"])
        )
            ->default(fn() => Auth::id())
            ->searchable()
            ->nullable(),
        ]);
    }
}