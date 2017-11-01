<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends ApiController
{
    public function getAuthUser()
    {

        /** @var User $user */
        $user = Auth::user();

        return $this->respond(User::with('roles')->whereId($user->id)->get()->first());
    }

    public function index()
    {

        /** @var User $user */
        $user = Auth::user();
        if ($user->can('index', $user))
        {
            return $this->respond(User::with('roles')->get());
        }

        return $this->respondNotFound();

    }

    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $newUser = new User ($request->except('password', 'password_confirmation'));
        $newUser->password = password_hash($request->password, PASSWORD_BCRYPT);
        try
        {
            $newUser->saveAsRoot();
        } catch (\Exception $e)
        {
            return $this->respondResourceConflict('User already exists');
        }


        $newUser->roles()->attach(2);

        $newUser::fixTree();

        return $this->respondResourceCreated($newUser);
    }

    public function show($id)
    {
        $descendant = User::find($id);
        if (Auth::user()->can('show', $descendant))
        {
            return $this->respond(User::with('roles')->find($id));
        }

        return $this->respondNotFound();

    }

    public function update(Request $request, $id)
    {
        $descendant = User::find($id);

        if (Auth::user()->can('update', $descendant))
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
        if (Auth::user()->can('delete', $user))
        {
            User::find($id)->delete();

            return $this->respondResourceDeleted();
        }

        return $this->respondNotFound();
    }
}
