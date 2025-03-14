<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Traits\SendResponse;

class RoleController extends BaseController
{


    public function index()
    {

        $roles = Role::all();

        return $this->sendResponse(
            $roles,
            'Roles retrieved successfully',
            200
        );
    }
}
