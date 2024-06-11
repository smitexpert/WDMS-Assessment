<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CurrencyController extends Controller
{
    public function index(CurrencyService $currencyService) {

        try {
            $currencies = $currencyService->availableForUser(Auth::user());
            return response()->success($currencies);
        } catch (\Throwable $th) {
            return response()->error($th->getMessage(), [], 503);
        }

    }
}
