<?php

namespace App\Repository;

use App\Entity\Result\Result;
use App\Entity\Result\ResultRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Result>
 */
class ResultRepository extends ServiceEntityRepository implements ResultRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Result::class);
    }

    public function create(Result $result): Result
    {
        $this->getEntityManager()->persist($result);
        $this->getEntityManager()->flush();

        return $result;
    }
}
