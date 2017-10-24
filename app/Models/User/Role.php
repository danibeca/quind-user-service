<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $hidden = array('pivot','created_at','updated_at');
}
