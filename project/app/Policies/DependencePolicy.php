<?php

namespace App\Policies;

use App\Models\Dependence;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DependencePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Dependence  $dependence
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Dependence $dependence)
    {
        // return $user->id === $dependence->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Dependence  $dependence
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Dependence $dependence)
    {
        return $user->id === $dependence->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Dependence  $dependence
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Dependence $dependence)
    {
        return $user->id === $dependence->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Dependence  $dependence
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Dependence $dependence)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Dependence  $dependence
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Dependence $dependence)
    {
        //
    }
}
