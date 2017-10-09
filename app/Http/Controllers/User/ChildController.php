<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ChildController extends ApiController
{

    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        return $this->respond($user->getDescendants());
    }

    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $existingUser = User::withTrashed()->where('email', $request->email)->first();
        if ($existingUser)
        {
            if ($user && $user->can('restoreChild', $user))
            {
                $existingUser->restore();

                return $this->respondResourceRestored();
            } else
            {
                return $this->respondResourceConflict('User already exists');
            }
        } else
        {
            $existingUser = new User ($request->except('password', 'password_confirmation'));
            $existingUser->password = password_hash($request->password, PASSWORD_BCRYPT);
            $existingUser->appendToNode($user)->save();
            $existingUser->save();
            User::fixTree();

            return $this->respondResourceCreated();
        }

    }

    public function show($id)
    {
        /** @var User $user */
        $user = Auth::user();
        $descendant = User::find($id);
        if ($user && $user->can('showChild', $descendant))
        {
            return $this->respond($descendant);
        }
        return $this->respondNotFound();
    }

    public function update(Request $request, $id)
    {
        $descendant = User::find($id);

        if (Auth::user()->can('updateChild', $descendant))
        {
            if ($request->has('password'))
            {
                $password = $request->password;
                $passwordConfirmation = $request->password_confirmation;
                if ($password === $passwordConfirmation)
                {
                    $descendant->password = password_hash($request->password, PASSWORD_BCRYPT);
                }
            }
            $descendant->update($request->except('password', 'password_confirmation'));

            return $this->respond($descendant);
        }

        return $this->respondNotFound();


    }

    public function destroy($id)
    {
        /** @var User $user */
        $user = Auth::user();
        if (Auth::user()->can('deleteChild', $user))
        {
            User::find($id)->delete();

            return $this->respondResourceDeleted();
        }

        return $this->respondNotFound();

    }
}
