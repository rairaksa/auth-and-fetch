<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\JWTManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        // validate request
        $validation = Validator::make($request->all(), [
            'nik' => 'required|unique:users|min:16|max:16'
        ]);

        if($validation->fails()){
            return response()->json([
                'status'    => 'failed',
                'message'   => $validation->errors()
            ]);
        }

        // create user
        $password = $this->random_string(6);

        $user = new User;
        $user->nik = $request->nik;
        $user->role = $request->role;
        $user->password = Hash::make($password);

        if($user->save()) {
            return response()->json([
                'status'    => 'success',
                'data'      => [
                    'nik'       => $user->nik,
                    'role'      => $user->role,
                    'password'  => $password
                ]
            ]);
        } else {
            return response()->json([
                'status'    => 'success',
                'message'   => 'User failed to create'
            ], 500);
        }
    }

    public function login(Request $request)
    {
        // validate request

        // check user
        $user = User::where([
            'nik' => $request->nik
        ])->first();

        if($user && Hash::check($request->password, $user->password)) {
            // create jwt
            $data = [
                'id'    => $user->id,
                'nik'   => $user->nik,
                'role'  => $user->role
            ];

            $token = JWTManager::create($data);

            // return response
            return response()->json([
                'status'    => 'success',
                'data'      => [
                    'id'        => $user->id,
                    'nik'       => $user->nik,
                    'role'      => $user->role,
                    'token'     => $token
                ]
            ]);

        } else {
            // return failed response
            return response()->json([
                'status'    => 'failed',
                'message'   => 'User not found'
            ], 400);
        }
    }

    public function check()
    {
        $credentials = JWTManager::decode_from_request();

        $data = $credentials->data;

        return response()->json([
            'status'    => 'success',
            'data'      => [
                'id'        => $data->id,
                'nik'       => $data->nik,
                'role'      => $data->role
            ]
        ]);
    }

    private function random_string($length)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }
}
