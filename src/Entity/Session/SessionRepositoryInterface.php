<?php

declare(strict_types=1);

namespace App\Entity\Session;

interface SessionRepositoryInterface
{
    public function create(Session $session): Session;
}
