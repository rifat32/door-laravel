<?php

namespace App\Http\Services;

use App\Http\Utils\ErrorUtil;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

trait UserServices
{
    use ErrorUtil;
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
    public function updateUserService($request)
    {
        try{
            $updatableData = $request->validated();
        $user =    User::where(["id" =>  $updatableData["id"]])
            ->first();
            $user->first_name = $updatableData["first_name"];
            $user->last_name = $updatableData["last_name"];
            $user->discount = $updatableData["discount"];
            if(!empty($updatableData["password"])) {
                $user->password = Hash::make($updatableData['password']);
            }
            $user->removeRole($user->roles->first());
            $user->assignRole($updatableData['role_name']);
            $data = $user;
            return response()->json($data, 200);
        } catch(Exception $e){
        return $this->sendError($e,500);
        }
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
        $users = User::with("roles")
        ->whereHas('roles', function ($query) {
            return $query->where('name','!=', 'customer');
        })
        ->orderByDesc("id")->paginate(10);
        return response()->json(["users" => $users], 200);
    }
    public function deleteUserService($request, $id)
    {
        User::where(["id" => $id])->delete();
        return response()->json(["ok" => true], 200);
    }
    public function searchUserService($term, $request)
    {
        try {
            $data['data'] =   User::with("roles")
    ->where(function($query) use ($term){
        $query->where("name", "like", "%" . $term . "%");
        $query->orWhere("email", "like", "%" . $term . "%");
    })

                ->latest()
                ->paginate(10);

            return response()->json($data, 200);
        } catch (Exception $e) {
            return $this->sendError($e, 500);
        }
    }
}
