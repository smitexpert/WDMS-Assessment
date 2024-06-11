<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{

    public function __construct(protected TransactionService $transactionService) {

    }

    public function index() {
        $transactions = $this->transactionService->getTransactions(Auth::user());
        return response()->success($transactions);
    }
}
