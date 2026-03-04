<?php

namespace App\Filament\Resources\RapportsDisciplinaires\Schemas;

use App\Models\Etudiant;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class RapportDisciplinaireForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
            Select::make('etudiant_id')
            ->label('Étudiant concerné')
            ->required()
            ->searchable()
            ->getSearchResultsUsing(function (string $search) {
            return Etudiant::where('nom', 'like', "%{$search}%")
                ->orWhere('prenom', 'like', "%{$search}%")
                ->limit(20)
                ->get()
                ->mapWithKeys(fn($e): array => [
            (string)$e->id => (string)"{$e->nom} {$e->prenom}"
            ]);
        })
            ->getOptionLabelUsing(fn($value): string => (string)(optional(Etudiant::find($value))->nom . ' ' . optional(Etudiant::find($value))->prenom))
            ->columnSpanFull(),

            Select::make('type')
            ->options([
                'Comportement' => 'Comportement',
                'Violence' => 'Violence',
                'Dégradation' => 'Dégradation',
                'Autre' => 'Autre',
            ])
            ->required(),

            TextInput::make('gravite')
            ->label('Gravité (0-10)')
            ->numeric()
            ->minValue(0)
            ->maxValue(10)
            ->default(0)
            ->required(),

            Textarea::make('description')
            ->required()
            ->columnSpanFull(),

            Select::make('statut')
            ->options([
                'Nouveau' => 'Nouveau',
                'En cours' => 'En cours',
                'Clôturé' => 'Clôturé',
            ])
            ->default('Nouveau')
            ->required(),

            Select::make('rapporte_par_id')
            ->label('Rapporté par')
            ->options(User::whereIn('role', ['Administratif', 'Concierge'])->pluck('email', 'id'))
            ->default(fn() => Auth::id())
            ->required()
            ->searchable(),
        ]);
    }
}