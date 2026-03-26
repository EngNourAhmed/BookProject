<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPublishingPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        // 1. لو مش writer ولا admin → ممنوع ينشر
        if (!in_array($user->role, ['writer', 'admin'])) {
            return response()->json([
                'status' => false,
                'message' => 'You are not allowed to publish content.'
            ], 403);
        }

        // 2. لو writer → لازم يكون publisher approved
        if ($user->role === 'writer' && !$user->publisher_approved) {
            return response()->json([
                'status' => false,
                'message' => 'Your publishing request has not been approved yet.',
            ], 403);
        }

        // 3. admin ينشر عادي
        return $next($request);
    }
}
