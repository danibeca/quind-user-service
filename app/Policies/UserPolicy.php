<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization, ChildPolicy;

    /**
     * Determine whether the user can index users.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function index(User $user)
    {
        return $user->roles->where('id', 1)->count() > 0;
    }

    /**
     * Determine whether the user can create users.
     *
     * @param  \App\User $parent
     * @param  \App\User $descendant
     * @return mixed
     */
    public function show(User $parent, User $descendant)
    {
        return $this->generalCheck($parent, $descendant);

    }

    /**
     * Determine whether the user can update the user.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function update(User $parent, User $descendant)
    {
        return $this->generalCheck($parent, $descendant);
    }

    /**
     * Determine whether the user can delete the user.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->roles->where('id', 1)->count() > 0;
    }


    public function generalCheck(User $parent, User $descendant)
    {
        $result = ($parent->roles->where('id', 1)->count() > 0) ? true : $parent->id === $descendant->id;

        if (! $result)
        {
            $result = $descendant->isDescendantOf($parent);
        }

        return $result;
    }
}
