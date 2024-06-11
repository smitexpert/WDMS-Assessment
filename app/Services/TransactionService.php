<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Repositories\TransactionRepository;

class TransactionService {

    protected $transactionRepository;

    public function __construct() {
        $this->transactionRepository = new TransactionRepository();
    }

    public function addBalance(User $user, $data) {
        return $this->transactionRepository->create($user, $data, 'add');
    }

    public function withdrawBalance(User $user, $data) {
        return $this->transactionRepository->create($user, $data, 'withdraw');
    }

    public function getTransactions(User $user) {
        return $this->transactionRepository->getTransactionsByUser($user);
    }

    public function getTransactionsByWallet(User $user, Wallet $wallet) {
        return $this->transactionRepository->getTransactionsByWallet($user, $wallet);
    }

}
