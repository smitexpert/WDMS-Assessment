<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InsureUserEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if(!auth()->check()) {
            abort(401);
        }

        if(auth()->user()->email_verified_at == null && (auth()->user()->role_id == 2)) {
            return response()->error('Your email address is not verified.', [
                'response' => 'Your email address is not verified.',
               'response_code' => '40441'
            ], 403);
        }

        return $next($request);
    }
}
