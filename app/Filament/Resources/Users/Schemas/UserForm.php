<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
            TextInput::make('email')
            ->label('Adresse email')
            ->email()
            ->required()
            ->maxLength(255),
            TextInput::make('password')
            ->label('Mot de passe')
            ->password()
            ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null)
            ->dehydrated(fn($state) => filled($state))
            ->placeholder('Laisser vide pour ne pas changer')
            ->maxLength(255),
            Select::make('role')
            ->label('Rôle')
            ->options([
                'Administratif' => 'Administratif',
                'Comptable' => 'Comptable',
                'Concierge' => 'Concierge',
                'Technicien' => 'Technicien',
                'Etudiant' => 'Etudiant',
            ])
            ->required(),
            Toggle::make('is_active')
            ->label('Compte actif')
            ->default(true),
        ]);
    }
}