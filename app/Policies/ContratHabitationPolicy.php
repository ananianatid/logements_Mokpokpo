<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ContratHabitation;

class ContratHabitationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isComptable() || $user->isConcierge();
    }

    public function view(User $user, ContratHabitation $model): bool
    {
        return $user->isAdmin() || $user->isComptable() || $user->isConcierge();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, ContratHabitation $model): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, ContratHabitation $model): bool
    {
        return $user->isAdmin();
    }
}