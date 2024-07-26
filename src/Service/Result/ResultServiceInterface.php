<?php

declare(strict_types=1);

namespace App\Service\Result;

use App\Entity\Answer;
use App\Entity\Result\Result;
use App\Entity\Session\Session;
use App\Entity\TestCase;

interface ResultServiceInterface
{
    public function create(Session $session, TestCase $testCase, Answer $answer): Result;
}
