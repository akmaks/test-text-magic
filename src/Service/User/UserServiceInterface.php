<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User\User;

interface UserServiceInterface
{
    public function getOrCreateUser(string $name): User;
}
