<?php

namespace App\Policies;

use App\Models\User;
use App\Models\DemandeLogement;

class DemandeLogementPolicy
{
    public function viewAny(User $user): bool
    {
        // Seul l'admin traite les demandes
        return $user->isAdmin();
    }

    public function view(User $user, DemandeLogement $model): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, DemandeLogement $model): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, DemandeLogement $model): bool
    {
        return $user->isAdmin();
    }
}