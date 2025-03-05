<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class AuthenticateAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        $record = PersonalAccessToken::findToken($token);
        if ($record) {
            return $next($request);
        } else {
            return response()->json(['message' => "Invalid Token"], 401);
        }
    }
}
