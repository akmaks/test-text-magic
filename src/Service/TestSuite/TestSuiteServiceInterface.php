<?php

declare(strict_types=1);

namespace App\Service\TestSuite;

use App\Entity\TestSuite\TestSuite;
use Doctrine\Common\Collections\Collection;

interface TestSuiteServiceInterface
{
    public function getTestSuiteByName(string $name): TestSuite;

    /**
     * @return Collection<int,TestSuite>
     */
    public function getList(): Collection;
}
