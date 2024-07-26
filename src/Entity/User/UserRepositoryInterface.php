<?php

declare(strict_types=1);

namespace App\Entity\User;

interface UserRepositoryInterface
{
    public function create(User $user): User;

    public function findOneByName(string $name): ?User;
}
