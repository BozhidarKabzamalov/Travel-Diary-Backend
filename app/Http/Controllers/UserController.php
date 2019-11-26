<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\User;
use JWTAuth;

class UserController extends Controller
{
    public function signup(Request $request) {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
            'email' => 'required|email|unique:users'
        ]);

        $user = new User();
        $user->username = $request['username'];
        $user->password = bcrypt($request['password']);
        $user->email = $request['email'];

        $user->save();
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'Successfully created a user!',
            'userId' => $user->id,
            'username' => $user->username,
            'token' => $token
        ], 201);
    }

    public function signin(Request $request) {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only('username', 'password');
        $user = User::where('username', $request['username'])->first();

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'error' => 'Invalid Credentials!'
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Could not create token!'
            ], 500);
        };

        return response()->json([
            'message' => 'Successfully created a token!',
            'userId' => $user->id,
            'username' => $user->username,
            'token' => $token
        ], 200);

    }
}
