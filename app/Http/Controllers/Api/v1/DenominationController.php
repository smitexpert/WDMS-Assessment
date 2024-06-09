<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDenominationRequest;
use App\Http\Requests\RemoveDenominationRequest;
use App\Services\DenominationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DenominationController extends Controller
{

    public function __construct(protected DenominationService $denominationService)
    {

    }

    public function index($wallet_id) {
        $denominations = $this->denominationService->userWalletDenominations(Auth::user(), $wallet_id);

        return response()->success($denominations);
    }

    public function store(CreateDenominationRequest $request) {

        try {
            $this->denominationService->addDenomination(Auth::user(), $request->only(['wallet_id', 'name', 'denomination', 'quantity']));

            return response()->success("Denomination was successfully");

        } catch (\Throwable $th) {
            return response()->error($th->getMessage(), [], 503);
        }

    }


    public function remove(RemoveDenominationRequest $request) {
        try {
            $this->denominationService->removeDenomination(Auth::user(), $request->only(['wallet_id', 'denomination_id']));

            return response()->success("Denomination was successfully");

        } catch (\Throwable $th) {
            return response()->error($th->getMessage(), [], 503);
        }
    }
}
