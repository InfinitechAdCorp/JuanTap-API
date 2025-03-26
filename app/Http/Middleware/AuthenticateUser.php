<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class AuthenticateUser
{
    public function handle(Request $request, Closure $next)
    {
        $id = $request->header('user-id');
        $record = User::find($id);
        if ($record) {
            if (($request->isMethod('post') || $request->isMethod('put')) && $record->role == "User") {
                $request->request->add(['user_id' => $id]);
            }
            return $next($request);
        } else {
            return response()->json(['message' => "Invalid User ID"], 401);
        }
    }
}
