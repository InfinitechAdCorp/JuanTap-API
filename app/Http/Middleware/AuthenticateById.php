<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class AuthenticateById
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user_id = $request->header('user-id');
        $user = User::find($user_id);
        if ($user) {
            return $next($request);
        } 
        return response()->json(['message' => "Invalid User ID"], 401);
    }
}
