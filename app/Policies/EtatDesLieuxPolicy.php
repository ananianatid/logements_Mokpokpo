<?php

namespace App\Policies;

use App\Models\User;
use App\Models\EtatDesLieux;

class EtatDesLieuxPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isConcierge();
    }

    public function view(User $user, EtatDesLieux $model): bool
    {
        return $user->isAdmin() || $user->isConcierge();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isConcierge();
    }

    public function update(User $user, EtatDesLieux $model): bool
    {
        return $user->isAdmin() || $user->isConcierge();
    }

    public function delete(User $user, EtatDesLieux $model): bool
    {
        return $user->isAdmin();
    }
}