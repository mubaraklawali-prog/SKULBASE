<?php

namespace App\Policies;

use App\Models\User;

class PlanPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'super_admin';
    }

    public function view(User $user): bool
    {
        return $user->role === 'super_admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'super_admin';
    }

    public function update(User $user): bool
    {
        return $user->role === 'super_admin';
    }

    public function delete(User $user): bool
    {
        return $user->role === 'super_admin';
    }
}
