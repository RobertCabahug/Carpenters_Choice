<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class AuthTokenFails
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        
        $accessToken = PersonalAccessToken::findToken( $request->bearerToken());

        if(!$accessToken)
            return response()->json([
                    'error' => [
                    'message' => 'Invalid Credentials!',
                ]
            ], 401);

        $request->attributes->set('sanctum_token', $accessToken);

        
        Auth::login($accessToken->tokenable);
        

        return $next($request);
    }
}

//10|zyE7QA0UMvrd9nJNHgPvpHVMRjJOLlwEYX3REsmyfa563a1c
