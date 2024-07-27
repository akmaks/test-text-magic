<?php

namespace App\Repository;

use App\Entity\Session\Session;
use App\Entity\Session\SessionRepositoryInterface;
use App\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Session>
 */
class SessionRepository extends ServiceEntityRepository implements SessionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    public function create(Session $session): Session
    {
        $this->getEntityManager()->persist($session);
        $this->getEntityManager()->flush();

        return $session;
    }
}
