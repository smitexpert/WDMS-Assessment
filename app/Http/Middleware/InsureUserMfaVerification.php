<?php

namespace App\Http\Middleware;

use App\Repositories\UserMfaVerifyRepository;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InsureUserMfaVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $providers = request()->user()->load('mfaProviders');

        if($providers->mfaProviders->count() > 0) {
            $mfaVerify = new UserMfaVerifyRepository();

            if(!$mfaVerify->checkUserToken(request()->user(), request()->user()->token()->id))
                return response()->error('You are not MFA verified.', [
                    'response' => 'You are not MFA verified.',
                    'response_code' => '40442'
                ], 403);
        }

        return $next($request);
    }
}
