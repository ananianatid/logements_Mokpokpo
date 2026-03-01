<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Batiment;

class BatimentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isStaff();
    }

    public function view(User $user, Batiment $model): bool
    {
        return $user->isStaff();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Batiment $model): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Batiment $model): bool
    {
        return $user->isAdmin();
    }
}