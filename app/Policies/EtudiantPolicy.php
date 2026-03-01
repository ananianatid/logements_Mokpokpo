<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Etudiant;

class EtudiantPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isComptable() || $user->isConcierge();
    }

    public function view(User $user, Etudiant $model): bool
    {
        return $user->isAdmin() || $user->isComptable() || $user->isConcierge();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Etudiant $model): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Etudiant $model): bool
    {
        return $user->isAdmin();
    }
}