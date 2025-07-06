<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Repository\ExpenseRepository;
use App\Service\BalanceService;
use PHPUnit\Framework\TestCase;

class BalanceServiceTest extends TestCase
{
    public function testGetUserBalance(): void
    {
        $user = new User();
        $repo = $this->createMock(ExpenseRepository::class);
        $repo->method('findTotalAmountByUser')->willReturn(123.45);

        $service = new BalanceService($repo);
        $this->assertSame(123.45, $service->getUserBalance($user));
    }

    public function testIsInMinus(): void
    {
        $user = new User();
        $repo = $this->createMock(ExpenseRepository::class);
        $repo->method('findTotalAmountByUser')->willReturn(-50);

        $service = new BalanceService($repo);
        $this->assertTrue($service->isInMinus($user));
    }
}
