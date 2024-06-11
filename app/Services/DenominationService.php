<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\DenominationRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class DenominationService {

    protected $denomationRepository;
    protected $userWalletService;
    protected $transactionService;

    public function __construct()
    {
        $this->denomationRepository = new DenominationRepository();
        $this->userWalletService = new UserWalletService();
        $this->transactionService = new TransactionService();
    }

    public function userWalletDenominations(User $user, $wallet_id) {
        $wallet = $this->userWalletService->isUserWallet($user, $wallet_id);

        if(!$wallet)
            throw new Exception("Invalid wallet");

        return $this->denomationRepository->findByWalletId($wallet);
    }

    public function addDenomination(User $user, array $data) {

        $wallet = $this->userWalletService->isUserWallet($user, $data['wallet_id']);

        if(!$wallet)
            throw new Exception("Invalid wallet");

        try {
            DB::beginTransaction();

            $denomination = $this->denomationRepository->create($user, $data);

            $transaction = $this->transactionService->addBalance($user, [
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'amount' => $denomination->denomination * $denomination->quantity,
            ]);

            $this->userWalletService->updateDenominationWalletBalance($wallet, $transaction);
            DB::commit();



            return true;

        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }


    public function removeDenomination(User $user, array $data) {

        $wallet = $this->userWalletService->isUserWallet($user, $data['wallet_id']);

        if(!$wallet)
            throw new Exception("Invalid wallet");

        $denomination = $this->denomationRepository->findByWalletAndId($wallet, $data['denomination_id']);

        if(!$denomination)
            throw new Exception("Invalid denomination");

        if($wallet->balance < ($denomination->denomination * $denomination->quantity))
            throw new Exception("Insufficient balance");

        try {
            DB::beginTransaction();

            $transaction = $this->transactionService->withdrawBalance($user, [
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'amount' => $denomination->denomination * $denomination->quantity,
            ]);

            $this->denomationRepository->delete($denomination);

            $this->userWalletService->updateDenominationWalletBalance($wallet, $transaction);
            DB::commit();


            return true;

        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

}
