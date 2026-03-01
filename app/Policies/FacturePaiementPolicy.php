<?php

namespace App\Policies;

use App\Models\User;
use App\Models\FacturePaiement;

class FacturePaiementPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isComptable();
    }

    public function view(User $user, FacturePaiement $model): bool
    {
        return $user->isAdmin() || $user->isComptable();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isComptable();
    }

    public function update(User $user, FacturePaiement $model): bool
    {
        return $user->isAdmin() || $user->isComptable();
    }

    public function delete(User $user, FacturePaiement $model): bool
    {
        return $user->isAdmin();
    }
}