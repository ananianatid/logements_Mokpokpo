<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Incident;

class IncidentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isStaff();
    }

    public function view(User $user, Incident $model): bool
    {
        return $user->isStaff();
    }

    public function create(User $user): bool
    {
        // Les concierges et techniciens créent souvent les incidents
        return $user->isAdmin() || $user->isConcierge() || $user->isTechnicien();
    }

    public function update(User $user, Incident $model): bool
    {
        return $user->isAdmin() || $user->isConcierge() || $user->isTechnicien();
    }

    public function delete(User $user, Incident $model): bool
    {
        return $user->isAdmin();
    }
}