<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Http\Services\UserServices;

class UserController extends Controller
{
    use UserServices;
    public function createUser(UserRequest $request)
    {
        return $this->createUserService($request);
    }
    public function getUsers(Request $request)
    {
        return $this->getUsersService($request);
    }
    public function deleteUser(Request $request, $id)
    {
        return $this->deleteUserService($request, $id);
    }
}
