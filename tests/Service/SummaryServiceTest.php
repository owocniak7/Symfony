<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Repository\ExpenseRepository;
use App\Service\SummaryService;
use PHPUnit\Framework\TestCase;

class SummaryServiceTest extends TestCase
{
    public function testMonthlySummary(): void
    {
        $user = new User();
        $repo = $this->createMock(ExpenseRepository::class);
        $repo->method('findMonthlySummary')->willReturn([
            ['month' => 6, 'total' => 100],
            ['month' => 7, 'total' => 50],
        ]);

        $summary = new SummaryService($repo);
        $result = $summary->monthlySummary($user);
        $this->assertCount(2, $result);
    }

    public function testMaxExpenseReturnsNull(): void
    {
        $user = new User();
        $repo = $this->createMock(ExpenseRepository::class);
        $repo->method('findMaxExpense')->willReturn(null);

        $summary = new SummaryService($repo);
        $this->assertNull($summary->maxExpense($user));
    }
}
