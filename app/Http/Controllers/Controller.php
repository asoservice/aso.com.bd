<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use DB;
use App\Models\Role;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // public function index()
    // {
    //     $permissions = DB::table('permissions')->pluck('name')->toArray();
    //     $role = Role::find(1);
    //     $role->syncPermissions($permissions);

    //     return 'OK';

    // }
}
