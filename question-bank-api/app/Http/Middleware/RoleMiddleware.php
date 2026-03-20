<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = auth('api')->user();

        if (!$user instanceof User) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        if (empty($roles) || !$user->hasAnyRole($roles)) {
            return response()->json([
                'message' => 'Forbidden. You do not have the required role.'
            ], 403);
        }

        return $next($request);
    }
}
