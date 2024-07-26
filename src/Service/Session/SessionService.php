<?php

declare(strict_types=1);

namespace App\Service\Session;

use App\Entity\Session\Session;
use App\Entity\Session\SessionRepositoryInterface;
use App\Entity\TestSuite\TestSuite;
use App\Entity\User\User;
use DateTimeImmutable;

class SessionService implements SessionServiceInterface
{
    public function __construct(
        private readonly SessionRepositoryInterface $sessionRepository,
    ) {
    }

    public function create(User $user, TestSuite $testSuite): Session
    {
        $session = new Session();
        $session
            ->setUser($user)
            ->setTestSuite($testSuite)
            ->setCreatedAt(new DateTimeImmutable());

        return $this->sessionRepository->create($session);
    }
}
