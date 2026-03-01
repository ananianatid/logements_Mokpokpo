<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Logement;

class LogementPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isStaff();
    }

    public function view(User $user, Logement $model): bool
    {
        return $user->isStaff();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Logement $model): bool
    {
        // Le concierge peut modifier le statut (pour maintenance etc)
        // Mais Filament gère souvent tous les champs par défaut, donc on restreint à Admin ou Concierge
        return $user->isAdmin() || $user->isConcierge();
    }

    public function delete(User $user, Logement $model): bool
    {
        return $user->isAdmin();
    }
}