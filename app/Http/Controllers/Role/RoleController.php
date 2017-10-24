<?php

namespace App\Http\Controllers\Role;

use App\Models\User\Role;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class RoleController extends ApiController
{
    public function index()
    {
        return Role::where('id','!=', 1)->get();
    }

}
