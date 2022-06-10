<?php

namespace App\Support;

use Carbon\Carbon;
use Firebase\JWT\JWT;
use App\Exceptions\MissingAuthorizationTokenException;
use Exception;
use Firebase\JWT\Key;

class JWTManager
{
    /**
     * Create JWT.
     *
     * @param  mixed  $data
     * @return string
     */
    public static function create($data)
    {
        $payload = [
            'iss' => "laravel-jwt",
            'data' => $data,
            'iat' => Carbon::now()->getTimestamp(),
            'exp' => Carbon::now()->addHours(config('jwt.hour_exp'))->getTimestamp()
        ];

        return JWT::encode($payload, config('jwt.secret'), 'HS256');
    }

    public static function decode_from_request($request = null)
    {
        $token = $request ? $request->bearerToken() : request()->bearerToken();

        if (!$token) {
            throw new MissingAuthorizationTokenException;
        }

        return JWT::decode($token, new Key(config('jwt.secret'), 'HS256'));
    }
}
