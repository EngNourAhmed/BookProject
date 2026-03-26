<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Reader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || $request->user()->role !== 'reader') {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Access Denied. Readers Only.'], 403);
            }
            return redirect()->route('login')->withErrors(['unauthorized' => 'You do not have permission to access the Reader Dashboard.']);
        }

        return $next($request);
    }
}
