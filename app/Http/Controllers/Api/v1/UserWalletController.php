<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserWalletController extends Controller
{

    public function index() {
        $wallet = Wallet::with('currency', 'denominations')->where('user_id', Auth::user()->id)->get();
        return response()->success($wallet);
    }
}
