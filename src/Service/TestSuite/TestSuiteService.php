<?php

declare(strict_types=1);

namespace App\Service\TestSuite;

use App\Entity\TestSuite\Exception\TestSuiteNotFoundByNameException;
use App\Entity\TestSuite\TestSuite;
use App\Entity\TestSuite\TestSuiteRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class TestSuiteService implements TestSuiteServiceInterface
{
    public function __construct(
        private readonly TestSuiteRepositoryInterface $testSuiteRepository,
    ) {
    }

    public function getTestSuiteByName(string $name): TestSuite
    {
        $testSuite = $this->testSuiteRepository->findOneByName($name);

        if ($testSuite === null) {
            throw new TestSuiteNotFoundByNameException($name);
        }

        return $testSuite;
    }

    /**
     * @return Collection<int,TestSuite>
     */
    public function getList(): Collection
    {
        return new ArrayCollection($this->testSuiteRepository->getList());
    }
}
