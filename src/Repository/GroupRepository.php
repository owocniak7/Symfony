<?php

namespace App\Repository;

use App\Entity\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
    }

    public function findGroupWithUsers(): array
    {
        return $this->createQueryBuilder('g')
            ->leftJoin('g.users', 'u')
            ->addSelect('u')
            ->getQuery()
            ->getResult();
    }
}
