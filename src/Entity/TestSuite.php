<?php

namespace App\Entity;

use App\Repository\TestSuiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: TestSuiteRepository::class)]
class TestSuite
{
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var \Doctrine\Common\Collections\Collection<int,TestCase>
     */
    #[ORM\OneToMany(targetEntity: TestCase::class, mappedBy: 'testSuite')]
    private Collection $testCases;

    /**
     * @var \Doctrine\Common\Collections\Collection<int,Session>
     */
    #[ORM\OneToMany(targetEntity: Session::class, mappedBy: 'testSuite')]
    private Collection $sessions;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->testCases = new ArrayCollection();
        $this->sessions = new ArrayCollection();
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<int,TestCase>
     */
    public function getTestCases(): Collection
    {
        return $this->testCases;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<int,TestCase> $testCases
     * @return $this
     */
    public function setTestCases(Collection $testCases): static
    {
        $this->testCases = $testCases;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<int,Session>
     */
    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
