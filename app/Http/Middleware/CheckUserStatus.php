<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized, please login.'], 401);
        }

        // Check account status
        if ($user->status === 'suspended') {
            return response()->json(['message' => 'Your account is suspended. Please contact support.'], 403);
        }

        if ($user->status === 'banned') {
            return response()->json(['message' => 'Your account has been permanently banned for policy violations.'], 403);
        }

        // لو Active
        return $next($request);
    }
}