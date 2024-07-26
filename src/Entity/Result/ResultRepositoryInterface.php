<?php

declare(strict_types=1);

namespace App\Entity\Result;

interface ResultRepositoryInterface
{
    public function create(Result $result): Result;
}
