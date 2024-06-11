<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\CurrencyRepository;

class CurrencyService {

    public function __construct(protected CurrencyRepository $currencyRepository)
    {
    }

    public function availableForUser(User $user) {
        return $this->currencyRepository->availableForUser($user);
    }
}
