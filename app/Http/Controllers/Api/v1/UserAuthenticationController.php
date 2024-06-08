<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegistrationRequest;
use App\Repositories\UserRepository;
use App\Services\UserAuthenticationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserAuthenticationController extends Controller
{

    public function register(UserRegistrationRequest $request, UserAuthenticationService $userAuthenticationService) {

        try {

            DB::beginTransaction();

            $user = $userAuthenticationService->register($request->only(['name', 'email', 'password']));
            $userAuthenticationService->sendUserVerificationEmail($user);

            // TODO: Login the user after registration.

            DB::commit();

            $userAuthentication = $userAuthenticationService->userAuthAttempt($request->email, $request->password);

            if(!$userAuthentication)
                return response()->error('Invalid Usercredentials', ['response' => 'Invalid Usercredentials', 'response_code' => '40401']);

            return response()->success($userAuthentication);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }


    public function auth(Request $request, UserAuthenticationService $userAuthenticationService) {

        $userAuthentication = $userAuthenticationService->userAuthAttempt($request->email, $request->password);

        if(!$userAuthentication)
            return response()->error('Invalid Usercredentials', ['response' => 'Invalid Usercredentials', 'response_code' => '40401']);

        return response()->success($userAuthentication);
    }
}
