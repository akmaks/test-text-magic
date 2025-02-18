<?php

namespace App\Repository;

use App\Entity\TestSuite\TestSuite;
use App\Entity\TestSuite\TestSuiteRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TestSuite>
 */
class TestSuiteRepository extends ServiceEntityRepository implements TestSuiteRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TestSuite::class);
    }

    public function findOneByName(string $name): ?TestSuite
    {
        return $this
            ->createQueryBuilder('t')
            ->andWhere('t.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return array<TestSuite>
     */
    public function getList(): array
    {
        return $this->findAll();
    }
}
