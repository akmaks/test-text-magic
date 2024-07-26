<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User\User;
use App\Entity\User\UserRepositoryInterface;
use DateTimeImmutable;

class UserService implements UserServiceInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function getOrCreateUser(string $name): User
    {
        $user = $this->userRepository->findOneByName($name);

        if ($user instanceof User) {
            return $user;
        }

        $user = new User();
        $user->setName($name)->setCreatedAt(new DateTimeImmutable());

        return $this->userRepository->create($user);
    }
}
