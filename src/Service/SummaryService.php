<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\ExpenseRepository;

class SummaryService
{
    public function __construct(private ExpenseRepository $repo)
    {
    }

    public function monthlySummary(User $user): array
    {
        return $this->repo->findMonthlySummary($user);
    }

    public function categorySummary(User $user): array
    {
        return $this->repo->findExpensesGroupedByCategory($user);
    }

    public function recent(User $user, int $limit = 5): array
    {
        return $this->repo->findLastExpenses($user, $limit);
    }

    public function maxExpense(User $user): ?float
    {
        $expense = $this->repo->findMaxExpense($user);
        return $expense?->getAmount();
    }
}
