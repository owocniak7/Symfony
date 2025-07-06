<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\ExpenseRepository;

class BalanceService
{
    public function __construct(private ExpenseRepository $repo)
    {
    }

    public function getUserBalance(User $user): float
    {
        return $this->repo->findTotalAmountByUser($user);
    }

    public function isInMinus(User $user): bool
    {
        return $this->getUserBalance($user) < 0;
    }
}
