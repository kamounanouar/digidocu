<?php

namespace App\Policies;

use App\User;
use App\Prestation;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrestationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any prestations.
     *
     * @param \App\User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if ($user->can('read prestations')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can view the prestation.
     *
     * @param \App\User $user
     * @param \App\Prestation $prestation
     * @return mixed
     */
    public function view(User $user, Prestation $prestation)
    {
        if ($user->can('read prestations')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can create prestations.
     *
     * @param \App\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->can('create prestations')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can update the prestation.
     *
     * @param \App\User $user
     * @param \App\Prestation $prestation
     * @return mixed
     */
    public function update(User $user, Prestation $prestation)
    {
        if ($user->can('update prestations')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the prestation.
     *
     * @param \App\User $user
     * @param \App\Prestation $prestation
     * @return mixed
     */
    public function delete(User $user, Prestation $prestation)
    {
        if ($user->can('delete prestations')) {
            return true;
        } else {
            return false;
        }
    }
}
