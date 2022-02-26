<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use Illuminate\Http\Request;
use App\Http\Services\RolesServices;

class RolesController extends Controller
{
    use RolesServices;
    public function createRole(RoleRequest $request)
    {
        return $this->createRoleService($request);
    }
    public function getRoles(Request $request)
    {
        return $this->getRolesService($request);
    }
    public function getRolesAll(Request $request)
    {
        return $this->getAllRolesService($request);
    }
}
