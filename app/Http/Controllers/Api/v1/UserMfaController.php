<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\MfaEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\MfaSettingRequest;
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
}
