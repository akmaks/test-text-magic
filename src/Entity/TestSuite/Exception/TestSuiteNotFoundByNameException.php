<?php

declare(strict_types=1);

namespace App\Entity\TestSuite\Exception;

use DomainException;

class TestSuiteNotFoundByNameException extends DomainException
{
    public function __construct(string $name = "")
    {
        parent::__construct(
            sprintf('TestSuite %s not found', $name)
        );
    }
}
