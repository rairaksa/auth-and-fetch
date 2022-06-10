<?php

namespace App\Http\Middleware;

use Closure;
use Throwable;
use App\Support\JWTManager;
use Firebase\JWT\ExpiredException;
use App\Exceptions\MissingAuthorizationTokenException;

class JwtMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            JWTManager::decode_from_request();
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
}
