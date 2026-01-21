<?php

namespace App\Http\Middleware;

use Closure;
use Exception;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;


class RefreshTokenMiddleware
{
   public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['error'=>'token_invalid']);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                $token = JWTAuth::getToken();
                if (!$token) {
                    return response()->json(['error' =>'Token not provided']);
                }
                try {
                    $refreshedToken = JWTAuth::refresh($token);
                } catch (JWTException $e) {
                    return response()->json(['error' =>'Not able to refresh Token']);
                }

                return response()->json(['error'=>'token_expired','token'=>$refreshedToken]);
            }else{
                return response()->json(['error'=>'error']);
            }
        }
        return $next($request);
    }
}
