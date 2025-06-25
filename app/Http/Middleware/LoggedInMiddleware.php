<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class LoggedInMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('LoggedInMiddleware executed.');
        if (auth('api')->check()) {
            Log::info('User is already logged in.');
            return response([
                'message' => 'You are already logged in'
            ], 400);
        }
        return $next($request);
//        dd(auth()->user());
//        if (auth()->email == 'user1@user.com') {
//            // Return JSON response instead of redirecting
//            return response()->json([
//                'message' => 'You are already logged in.'
//            ], 403); // 403 Forbidden status code
//        }
//
//        // If not authenticated, proceed with the request
//        return $next($request);
    }

}
