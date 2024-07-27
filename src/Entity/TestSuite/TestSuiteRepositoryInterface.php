<?php

declare(strict_types=1);

namespace App\Entity\TestSuite;

interface TestSuiteRepositoryInterface
{
    public function findOneByName(string $name): ?TestSuite;

    /**
     * @return array<TestSuite>
     */
    public function getList(): array;
}
