<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Handicap;

class HandicapPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Handicap $model): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Handicap $model): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Handicap $model): bool
    {
        return $user->isAdmin();
    }
}