<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSupervisor() || $user->isSectoradmin();
    }

    public function view(User $user, User $model): bool
    {
        if ($user->isSupervisor()) return true;

        if ($user->isSectoradmin()) {
            return $user->sector === $model->sector;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isSupervisor() || $user->isSectoradmin();
    }

    public function update(User $user, User $model): bool
    {
        if ($user->isSupervisor()) return true;

        if ($user->isSectoradmin()) {
            // Cannot edit supervisors
            if ($model->isSupervisor()) return false;

            // Can only edit users within their own sector
            return $user->sector === $model->sector;
        }

        return false;
    }

    public function delete(User $user, User $model): bool
    {
        if ($user->isSupervisor()) return true;

        if ($user->isSectoradmin()) {
            if ($model->isSupervisor()) return false;
            return $user->sector === $model->sector;
        }

        return false;
    }

    public function restore(User $user, User $model): bool
    {
        if ($user->isSupervisor()) return true;

        if ($user->isSectoradmin()) {
            if ($model->isSupervisor()) return false;
            return $user->sector === $model->sector;
        }

        return false;
    }

    public function forceDelete(User $user, User $model): bool
    {
        if ($user->isSupervisor()) return true;

        if ($user->isSectoradmin()) {
            if ($model->isSupervisor()) return false;
            return $user->sector === $model->sector;
        }

        return false;
    }
}