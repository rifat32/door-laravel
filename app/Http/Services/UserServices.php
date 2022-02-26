<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

trait UserServices
{
    public function createUserService($request)
    {

        $request['password'] = Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        $user =  User::create($request->toArray());
        $user->assignRole($request['role_name']);
        return response()->json([
            "user" => $user
        ], 201);
    }
    public function getUsersService($request)
    {
        $users = User::with("roles")->orderByDesc("id")->paginate(10);
        return response()->json(["users" => $users], 200);
    }
    public function deleteUserService($request, $id)
    {
        User::where(["id" => $id])->delete();
        return response()->json(["ok" => true], 200);
    }
}
