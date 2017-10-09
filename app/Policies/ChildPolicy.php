<?php

namespace App\Policies;

use App\User;


trait ChildPolicy
{


    public function createChild(User $parent, User $descendant)
    {
        return $this->generalCheck($parent, $descendant);

    }

    public function showChild(User $parent, User $descendant)
    {
        return $this->generalCheck($parent, $descendant);

    }


    public function updateChild(User $parent, User $descendant)
    {
        return $this->generalCheck($parent, $descendant);
    }

    public function deleteChild(User $parent, User $descendant)
    {
        return $this->generalCheck($parent, $descendant);
    }


    public function generalCheck(User $parent, User $descendant)
    {
        return $descendant->isDescendantOf($parent);
    }
}
