<?php

declare(strict_types=1);

namespace App\Service\Result;

use App\Entity\Answer\Answer;
use App\Entity\Result\Result;
use App\Entity\Session\Session;

interface ResultServiceInterface
{
    public function create(Session $session, Answer $answer): Result;
}
