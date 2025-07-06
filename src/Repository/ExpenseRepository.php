<?php

namespace App\Repository;

use App\Entity\Expense;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ExpenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Expense::class);
    }

    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.user = :user')
            ->setParameter('user', $user)
            ->orderBy('e.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findTotalAmountByUser(User $user): float
    {
        return (float) $this->createQueryBuilder('e')
            ->select('SUM(e.amount)')
            ->where('e.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findMonthlySummary(User $user): array
    {
        return $this->createQueryBuilder('e')
            ->select('MONTH(e.date) as month, SUM(e.amount) as total')
            ->where('e.user = :user')
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function findByCategory(int $categoryId): array
    {
        return $this->createQueryBuilder('e')
            ->join('e.category', 'c')
            ->andWhere('c.id = :cat')
            ->setParameter('cat', $categoryId)
            ->getQuery()
            ->getResult();
    }

    public function findBetweenDates(\DateTime $from, \DateTime $to, User $user): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.date BETWEEN :from AND :to')
            ->andWhere('e.user = :user')
            ->setParameters([
                'from' => $from,
                'to' => $to,
                'user' => $user
            ])
            ->orderBy('e.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findLastExpenses(User $user, int $limit = 5): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.user = :user')
            ->setParameter('user', $user)
            ->orderBy('e.date', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findMaxExpense(User $user): ?Expense
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.user = :user')
            ->setParameter('user', $user)
            ->orderBy('e.amount', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findExpensesGroupedByCategory(User $user): array
    {
        return $this->createQueryBuilder('e')
            ->select('c.name AS category, SUM(e.amount) AS total')
            ->join('e.category', 'c')
            ->where('e.user = :user')
            ->groupBy('c.name')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}
