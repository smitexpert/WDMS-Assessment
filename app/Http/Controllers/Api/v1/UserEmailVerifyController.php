<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserEmailVerifyRequest;
use App\Services\UserAuthenticationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserEmailVerifyController extends Controller
{
    public function verifyEmail(UserEmailVerifyRequest $request, UserAuthenticationService $userAuthenticationService) {

        try {
            $result = $userAuthenticationService->verifyUserEmailCode(Auth::user(), $request->code);

            return response()->success([
                'response' => "User email verified"
            ]);

        } catch (\Throwable $th) {
            return response()->error($th->getMessage(), ['response' => $th->getMessage(),'response_code' => '50001'], 404);
        }
    }


    public function resendVerifyEmail(UserAuthenticationService $userAuthenticationService) {

        try {
            $userAuthenticationService->sendUserVerificationEmail(Auth::user());

            return response()->success(['response' => "Verification email sent!"]);

        } catch (\Throwable $th) {
            return response()->error($th->getMessage(), ['response' => $th->getMessage(),'response_code' => '40404'], 404);
        }

    }
}
