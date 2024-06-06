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

            DB::commit();

            return response()->success($user);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }
}
