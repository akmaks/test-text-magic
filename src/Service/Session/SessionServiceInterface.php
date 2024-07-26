<?php

declare(strict_types=1);

namespace App\Service\Session;

use App\Entity\Session\Session;
use App\Entity\TestSuite\TestSuite;
use App\Entity\User\User;

interface SessionServiceInterface
{
    public function create(User $user, TestSuite $testSuite): Session;
}
