<?php

namespace App\Http\Middleware;

use Closure;
use Throwable;
use App\Models\ClientKey;
use App\Support\JWTManager;
use Firebase\JWT\ExpiredException;
use App\Exceptions\MissingAuthorizationTokenException;
use App\Models\User;

class JwtMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            $credentials = JWTManager::decode_from_request();

            if (!$this->token_is_valid($credentials->data)) {
                return response()->json([
                    'message' => 'Token invalid'
                ], 404);
            }
        } catch (ExpiredException $e) {
            return response()->json([
                'message' => 'Provided token is expired'
            ], 400);
        } catch (MissingAuthorizationTokenException $e) {
            return response()->json([
                'message' => 'Token not provided'
            ], 401);
        } catch (Throwable $th) {
            return response()->json([
                'message' => 'An error while decoding token.'
            ], 400);
        }

        return $next($request);
    }

    protected function token_is_valid($data)
    {
        // check id and nik
        $user = User::where([
            'id'    => $data->id,
            'nik'   => $data->nik
        ])->first();

        if(!$user) {
            return false;
        }

        return true;
    }
}
