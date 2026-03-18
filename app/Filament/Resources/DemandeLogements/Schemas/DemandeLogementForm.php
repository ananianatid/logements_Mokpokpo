<?php

namespace App\Filament\Resources\DemandeLogements\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use App\Models\Logement;
use App\Models\Batiment;
use Filament\Schemas\Schema;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\DB;

class DemandeLogementForm
{
    public static function configure(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
            Select::make('etudiant_id')
            ->label('Étudiant')
            ->relationship('etudiant', 'nom')
            ->getOptionLabelFromRecordUsing(fn($record) => "{$record->nom} {$record->prenom}")
            ->searchable()
            ->required(),

            // ─── Sélection du bâtiment pour l'attribution ───────────────────
            Select::make('batiment_id')
            ->label('Bâtiment souhaité (par l\'étudiant)')
            ->relationship('batiment', 'nom')
            ->searchable()
            ->live()  // Déclenche la mise à jour réactive du select chambre
            ->helperText('Modifier ce champ pour filtrer les chambres disponibles ci-dessous.'),

            Select::make('type_logement_id')
            ->label('Type de chambre souhaité')
            ->relationship('type_logement', 'nom')
            ->searchable(),

            DateTimePicker::make('date_soumission')
            ->required()
            ->default(now()),

            Select::make('statut')
            ->options([
                'En attente' => 'En attente',
                'En cours'   => 'En cours',
                'Validée'    => 'Validée',
                'Rejetée'    => 'Rejetée',
            ])
            ->required()
            ->default('En attente'),

            TextInput::make('priorite')
            ->label('Priorité (0-10)')
            ->required()
            ->numeric()
            ->default(0),

            // ─── Logement attribué – filtré par le bâtiment sélectionné ─────
            Select::make('logement_propose_id')
            ->label('Logement attribué')
            ->options(fn(Get $get) =>
                Logement::with('type_logement')
                    ->where('batiment_id', $get('batiment_id'))
                    ->where('statut', 'Disponible')
                    ->get()
                    ->mapWithKeys(fn($logement) => [
                        $logement->id => "{$logement->numero_chambre} (" . ($logement->type_logement->nom ?? '?') . ")"
                    ])
            )
            ->searchable()
            ->hint('Uniquement les chambres disponibles du bâtiment choisi'),

            Textarea::make('note_traitement')
            ->label('Notes de l\'administration')
            ->columnSpanFull(),

            // ─── Section informations sur l'étudiant ────────────────────────
            Section::make('Informations sur l\'étudiant')
                ->description('Détails de l\'étudiant ayant fait la demande pour guider l\'attribution.')
                ->schema([
                    // Ligne 1 : identité & académique de base
                    Grid::make(4)->schema([
                        Placeholder::make('student_name')
                            ->label('Nom complet')
                            ->content(fn ($record) => $record?->etudiant
                                ? new HtmlString("<strong>{$record->etudiant->nom} {$record->etudiant->prenom}</strong>")
                                : '-'),

                        Placeholder::make('student_sexe')
                            ->label('Sexe')
                            ->content(fn ($record) => $record?->etudiant?->sexe ?? '-'),

                        Placeholder::make('student_situation_matrimoniale')
                            ->label('Situation matrimoniale')
                            ->content(fn ($record) => $record?->etudiant?->situation_matrimoniale ?? '-'),

                        Placeholder::make('student_annee_bac')
                            ->label('Année du BAC')
                            ->content(fn ($record) => $record?->etudiant?->annee_obtention_bac ?? '-'),
                    ]),

                    // Ligne 2 : origine & distances
                    Grid::make(3)->schema([
                        Placeholder::make('student_prefecture')
                            ->label('Préfecture d\'origine')
                            ->content(fn ($record) => $record?->etudiant?->prefecture_origine ?? '-'),

                        Placeholder::make('student_distance_lome')
                            ->label('Distance / Lomé')
                            ->content(function ($record) {
                                $prefecture = $record?->etudiant?->prefecture_origine;
                                if (!$prefecture) return '-';
                                $row = DB::table('distances_prefectures')
                                    ->where('prefecture', 'like', "%{$prefecture}%")
                                    ->first();
                                return $row ? new HtmlString("<strong>{$row->distance} km</strong>") : '— km';
                            }),

                        Placeholder::make('student_distance_prefecture')
                            ->label('Distance / Préfecture chef-lieu')
                            ->content(function ($record) {
                                $prefecture = $record?->etudiant?->prefecture_origine;
                                if (!$prefecture) return '-';
                                $row = DB::table('distances_prefectures')
                                    ->where('prefecture', 'like', "%{$prefecture}%")
                                    ->first();
                                return $row
                                    ? new HtmlString("{$row->region} — {$row->distance} km de Lomé")
                                    : '-';
                            }),
                    ]),

                    // Ligne 3 : handicap
                    Grid::make(1)->schema([
                        Placeholder::make('student_handicaps')
                            ->label('Handicap(s) déclaré(s)')
                            ->content(function ($record) {
                                $etudiant = $record?->etudiant;
                                if (!$etudiant) return '-';
                                $handicaps = $etudiant->handicaps;
                                if ($handicaps->isEmpty()) {
                                    return new HtmlString('<span style="color:#6b7280;">Aucun handicap déclaré</span>');
                                }
                                $badges = $handicaps->map(fn($h) =>
                                    '<span style="background:#fef3c7;color:#92400e;padding:2px 8px;border-radius:999px;font-size:0.85em;font-weight:600;margin-right:4px;">'
                                    . e($h->nom) . '</span>'
                                )->join('');
                                return new HtmlString($badges);
                            })
                            ->columnSpanFull(),
                    ]),
                ])
                ->hidden(fn ($record) => $record === null)
                ->collapsed(false)
                ->collapsible(),
        ]);
    }
}