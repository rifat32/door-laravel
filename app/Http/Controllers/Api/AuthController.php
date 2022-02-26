<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRegisterRequest;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(AuthRegisterRequest $request)
    {

        // $validator = Validator::make($request->all(), [
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|string|email|indisposable|max:255|unique:users',
        //     'password' => 'required|confirmed|string|min:6',
        // ]);
        // if ($validator->fails()) {

        //     return response(['errors' => $validator->errors()->all()], 422);
        // }
        $request['password'] = Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        $user =  User::create($request->toArray());
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $data["user"] = $user;
        $data["permissions"]  = $user->getAllPermissions()->pluck('name');
        $data["roles"] = $user->roles->pluck('name');
        return response(["ok" => true, "message" => "You have successfully registered", "data" => $data, "token" => $token], 200);
    }
    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($loginData)) {
            return response(['message' => 'Invalid Credentials'], 401);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        $user = auth()->user();
        $data["user"] = $user;
        $data["permissions"]  = $user->getAllPermissions()->pluck('name');
        $data["roles"] = $user->roles->pluck('name');
        return response()->json(['data' => $data, 'token' => $accessToken,   "ok" => true], 200);
    }
    public function logout()
    {
        if (Auth::check()) {
            Auth::user()->AauthAcessToken()->delete();
            return response()->json(["ok" => true]);
        }
    }
}
