<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Http\Controllers\ApiController;
use App\Utils\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChildController extends ApiController
{

    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        return $this->respond((new UserTransformer())->
        transformCollection(User::whereIn('id', $user->getDescendants()->pluck('id'))
            ->with('roles')->get()->toArray()));
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
            if ($request->role_id === 1)
            {
                return $this->respondResourceConflict('There was problem creating your user');
            }
            $newUser = new User ($request->except('password', 'password_confirmation', 'role_id'));
            $newUser->password = password_hash($request->password, PASSWORD_BCRYPT);
            $newUser->appendToNode($user);
            $newUser->save();
            $newUser->roles()->attach($request->role_id);
            User::fixTree();

            return $this->respondResourceCreated($newUser);
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
        /** @var User $descendant */
        $descendant = User::find($id);

        if (Auth::user()->can('updateChild', $descendant))
        {
            $descendant->fill($request->except('password', 'password_confirmation'));

            if ($request->has('password'))
            {
                $password = $request->password;
                $passwordConfirmation = $request->password_confirmation;
                if ($password === $passwordConfirmation)
                {
                    $descendant->password = password_hash($request->password, PASSWORD_BCRYPT);
                }
            }

            $descendant->roles()->sync([$request->get('role_id')]);
            $descendant->save();

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
