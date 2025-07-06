<?php

namespace App\Controller;

use App\Formatter\ApiFormatter;
use App\Service\BalanceService;
use App\Service\SummaryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/reports')]
class ReportController extends AbstractController
{
    #[Route('/balance', methods: ['GET'])]
    public function balance(BalanceService $balance): Response
    {
        $user = $this->getUser();
        return $this->json([
            'balance' => $balance->getUserBalance($user),
            'status' => $balance->isInMinus($user) ? 'minus' : 'plus',
        ]);
    }

    #[Route('/monthly', methods: ['GET'])]
    public function monthly(SummaryService $summary, ApiFormatter $formatter): Response
    {
        $user = $this->getUser();
        return $this->json($formatter->formatSummary($summary->monthlySummary($user)));
    }

    #[Route('/categories', methods: ['GET'])]
    public function categories(SummaryService $summary, ApiFormatter $formatter): Response
    {
        $user = $this->getUser();
        return $this->json($formatter->formatSummary($summary->categorySummary($user)));
    }

    #[Route('/recent', methods: ['GET'])]
    public function recent(SummaryService $summary, ApiFormatter $formatter): Response
    {
        $user = $this->getUser();
        return $this->json(array_map(
            [$formatter, 'formatExpense'],
            $summary->recent($user)
        ));
    }

    #[Route('/max', methods: ['GET'])]
    public function max(SummaryService $summary): Response
    {
        $user = $this->getUser();
        return $this->json(['max_expense' => $summary->maxExpense($user)]);
    }
}
