<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || $request->user()->role !== 'admin') {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Access Denied. Admins Only.'], 403);
            }
            return redirect()->route('login')->withErrors(['unauthorized' => 'You do not have permission to access this page.']);
        }

        return $next($request);
    }
}