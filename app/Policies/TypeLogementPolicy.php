<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TypeLogement;

class TypeLogementPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isStaff();
    }

    public function view(User $user, TypeLogement $model): bool
    {
        return $user->isStaff();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, TypeLogement $model): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, TypeLogement $model): bool
    {
        return $user->isAdmin();
    }
}