<?php

namespace App\Tests\Formatter;

use App\Entity\Category;
use App\Entity\Expense;
use App\Formatter\ApiFormatter;
use PHPUnit\Framework\TestCase;

class ApiFormatterTest extends TestCase
{
    public function testFormatExpense(): void
    {
        $category = (new Category())->setName('Test');
        $expense = (new Expense())
            ->setAmount(123.45)
            ->setDescription('Lunch')
            ->setDate(new \DateTime('2023-07-01'))
            ->setCategory($category);

        $formatter = new ApiFormatter();
        $data = $formatter->formatExpense($expense);

        $this->assertSame('Lunch', $data['description']);
        $this->assertSame('Test', $data['category']);
        $this->assertSame('2023-07-01', $data['date']);
    }
}
