<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\MfaEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\MfaSettingRequest;
use App\Http\Requests\UserMfaCodeVerifyRequest;
use App\Models\MfaProvider;
use App\Models\User;
use App\Services\UserAuthenticationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class UserMfaController extends Controller
{
    public function index() {

        $providers = MfaEnum::cases();
        return response()->success($providers);

    }

    public function store(MfaSettingRequest $request, UserAuthenticationService $userAuthenticationService) {
        $user = User::find(Auth::user()->id);

        $providers = array_column(MfaEnum::cases(), 'value');

        if(!in_array($request->provider, $providers))
            return response()->error('Provider not found', [], 404);

        $result = $userAuthenticationService->enableUserMfa($user, $request->provider);

        if(!$result) {
            return response()->error('Provider alredy set', [], 405);
        }

        return response()->success([
            'response' => "User MFA enabled"
        ]);
    }

    public function delete(MfaSettingRequest $request, UserAuthenticationService $userAuthenticationService) {
        $user = User::find(Auth::user()->id);

        $providers = array_column(MfaEnum::cases(), 'value');

        if(!in_array($request->provider, $providers))
            return response()->error('Provider not found', [], 404);

        $userAuthenticationService->removeUserMfa($user, $request->provider);

        return response()->success([
            'response' => "User MFA removed successfully"
        ]);
    }

    public function getUserProvider(UserAuthenticationService $userAuthenticationService) {
        $providers = $userAuthenticationService->getUserMfaProvider(Auth::user());

        return response()->success($providers);
    }

    public function send(MfaSettingRequest $request, UserAuthenticationService $userAuthenticationService) {
        $userMfaProvider = $userAuthenticationService->userMfaProviderByName(Auth::user(), $request->provider);

        if(!$userMfaProvider)
            return response()->error("User MFA provider not found", [], 404);

        $data = $userAuthenticationService->createUserMfaVerify(Auth::user(), $request->provider);
        $userAuthenticationService->sendUserMfaCode(Auth::user(), $data->code, $request->provider);

        return response()->success([
           'response' => "User MFA code sent"
        ]);
    }

    public function verify(UserMfaCodeVerifyRequest $request, UserAuthenticationService $userAuthenticationService) {

        try {
            $userAuthenticationService->verifyUserMfaCode(Auth::user(), $request->code, $request->user()->token()->id);

            return response()->success([
               'response' => "User MFA code verified"
            ]);

        } catch (\Throwable $th) {
            return response()->error($th->getMessage(), [], 503);
        }

    }
}
