<?php

declare(strict_types=1);

namespace App\Service\Result;

use App\Entity\Answer;
use App\Entity\Result\Result;
use App\Entity\Result\ResultRepositoryInterface;
use App\Entity\Session\Session;
use App\Entity\TestCase;
use DateTimeImmutable;

class ResultService implements ResultServiceInterface
{
    public function __construct(
        private readonly ResultRepositoryInterface $resultRepository,
    ) {
    }

    public function create(Session $session, TestCase $testCase, Answer $answer): Result
    {
        $result = new Result();
        $result
            ->setSession($session)
            ->setTestCase($testCase)
            ->setAnswer($answer)
            ->setCreatedAt(new DateTimeImmutable());

        return $this->resultRepository->create($result);
    }
}
