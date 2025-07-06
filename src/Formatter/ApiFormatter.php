<?php

namespace App\Formatter;

use App\Entity\Expense;

class ApiFormatter
{
    public function formatExpense(Expense $e): array
    {
        return [
            'id' => $e->getId(),
            'amount' => $e->getAmount(),
            'description' => $e->getDescription(),
            'date' => $e->getDate()->format('Y-m-d'),
            'category' => $e->getCategory()->getName(),
        ];
    }

    public function formatSummary(array $raw): array
    {
        return array_map(fn($row) => [
            'label' => $row['category'] ?? $row['month'],
            'total' => (float) $row['total'],
        ], $raw);
    }
}
